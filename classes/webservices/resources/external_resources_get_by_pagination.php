<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/digitalta/classes/resources.php');
require_once($CFG->dirroot . '/local/digitalta/classes/resource.php');

class external_resources_get_by_pagination extends external_api
{
    public static function resources_get_by_pagination_parameters()
    {
        return new external_function_parameters(
            [
                'pagenumber' => new external_value(PARAM_INT, 'page number', VALUE_REQUIRED),
                'filters' => new external_value(PARAM_TEXT, 'filters', VALUE_REQUIRED)
            ]
        );
    }

    public static function resources_get_by_pagination($pagenumber, $filters)
    {
        global $DB, $CFG;

        $limit = 20;
        $totalPages = 0;

        $filters = json_decode($filters, true);

        $resources = array();

        if (count($filters) > 0) {
            $resourceTypes = [];
            $themes = [];
            $tags = [];
            $authors = [];
            $langs = [];

            foreach ($filters as $filter) {
                if ($filter["type"] == "tag") {
                    $tags[] = '"' . $filter["value"] . '"';
                } else if ($filter["type"] == "theme") {
                    $themes[] = $filter["value"];
                } else if ($filter["type"] == "author") {
                    $authors[] = '"' . $filter["value"] . '"';
                } else if ($filter["type"] == "language") {
                    $langs[] = '"' . $filter["value"] . '"';
                } else if ($filter["type"] == "resource") {
                    $resourceTypes[] = $filter["value"];
                }
            }

            $havingSum = "";

            foreach ($themes as $theme) {
                if (strlen($havingSum) == 0){
                    $havingSum .= "HAVING";
                }
                $havingSum .= " (SUM(CASE WHEN modifier = 1 AND modifierinstance = " . $theme . " THEN 1 ELSE 0 END) > 0) OR ";
            }

            if (count($tags) > 0) {
                $tagsToSearch = '(' . implode(', ', $tags) . ')';
                $tagsExperiences = $DB->get_records_sql(
                    "SELECT * FROM mdl_digitalta_tags where name IN ".$tagsToSearch
                );
                $tagsId = array_keys($tagsExperiences);
                foreach ($tagsId as $tagId) {
                    if (strlen($havingSum) == 0){
                        $havingSum .= "HAVING";
                    }
                    $havingSum .= "(SUM(CASE WHEN modifier = 2 AND modifierinstance = " . $tagId . " THEN 1 ELSE 0 END) > 0) OR ";
                }
            }

            $query = preg_replace('/\s+OR\s*$/', '', $havingSum);

            $componentInstanceIds = ($havingSum == "")
                ? []
                : array_keys($DB->get_records_sql(
                    'WITH FilteredComponentInstances AS (
                        SELECT componentinstance
                        FROM mdl_digitalta_context
                        WHERE component = 3
                        GROUP BY componentinstance ' . $query . '
                    )
                    SELECT componentinstance,
                        GROUP_CONCAT(modifier) AS modifiers, 
                        GROUP_CONCAT(modifierinstance) AS modifierinstances
                    FROM mdl_digitalta_context
                    WHERE componentinstance IN (
                        SELECT componentinstance FROM FilteredComponentInstances
                    )
                    GROUP BY componentinstance;'
                ));

            $components = [];

            if (count($componentInstanceIds) > 0 || count($authors) > 0 || count($langs) > 0 || count($resourceTypes) > 0) {

                $sqlComponent = 'SELECT * FROM mdl_digitalta_resources WHERE ';
                $sqlTotalRows = 'SELECT COUNT(*) AS total  FROM mdl_digitalta_resources WHERE ';

                // Patch - Do not show resources of type Study Case
                $studycase_type = \local_digitalta\Resources::get_type_by_name('Study Case')->id;
                if (!in_array($studycase_type, $resourceTypes)) {
                    $sqlComponent .= 'type != ' . $studycase_type . ' and ';
                    $sqlTotalRows .= 'type != ' . $studycase_type . ' and ';
                }
                $sqlComponent .= '(';
                $sqlTotalRows .= '(';

                foreach ($componentInstanceIds as $componentInstanceId) {
                    $sqlComponent .= ' or id = ' . $componentInstanceId;
                    $sqlTotalRows .= ' or id = ' . $componentInstanceId;
                }

                foreach ($authors as $author) {
                    $sqlComponent .= ' or userid = ' . $author;
                    $sqlTotalRows .= ' or userid = ' . $author;
                }

                foreach ($langs as $lang) {
                    $sqlComponent .= ' or lang like ' . $lang;
                    $sqlTotalRows .= ' or lang like ' . $lang;
                }

                foreach ($resourceTypes as $resourceType) {
                    $sqlComponent .= ' or type = ' . $resourceType;
                    $sqlTotalRows .= ' or type = ' . $resourceType;
                }

                $sqlComponent .= ')';
                $sqlTotalRows .= ')';

                $sqlComponent = preg_replace('/\(\s*or/', '(', $sqlComponent);
                $sqlTotalRows = preg_replace('/\(\s*or/', '(', $sqlTotalRows);

                $sqlComponent = preg_replace('/\s*and\s*\(\)/', '', $sqlComponent);
                $sqlTotalRows = preg_replace('/\s*and\s*\(\)/', '', $sqlTotalRows);

                $sqlComponent .= ' ORDER BY timecreated DESC limit ' . $limit . ' offset ' . (($pagenumber - 1) * $limit);

                $components = array_values($DB->get_records_sql($sqlComponent));

            }

            $totalRows = $DB->get_record_sql($sqlTotalRows)->total;

            $totalPages = ceil($totalRows / $limit);

            $resources = self::get_resources($components);
        } else {
            $studycase_type = \local_digitalta\Resources::get_type_by_name('Study Case')->id;
            $components = array_values(
                $DB->get_records_sql(
                    "SELECT * FROM mdl_digitalta_resources WHERE type != " . $studycase_type . " ORDER BY timecreated DESC LIMIT " . $limit . " OFFSET " . (($pagenumber - 1) * $limit)
                )
            );

            $totalRows = $DB->count_records('digitalta_resources');
            $totalPages = ceil($totalRows / $limit);
            $resources = self::get_resources($components);
        }

        return array(
            'data' => $resources,
            'pages' => $totalPages,
            'pagenumber' => $pagenumber
        );
    }

    public static function get_resources($components)
    {
        $resources = array();

        for ($i = 0; $i < count($components); $i++) {
            $resource = $components[$i];
            $resource_model = new \local_digitalta\Resource($resource);
            $x = \local_digitalta\Resources::get_extra_fields($resource_model);

            [$x->type, $x->type_simplified] = local_digitalta_get_element_translation(
                'resource_type',
                \local_digitalta\Resources::get_type($x->type)->name
            );

            $resources[] = $x;
        }

        return $resources;
    }

    public static function resources_get_by_pagination_returns()
    {
        return new external_single_structure([
            'data' => new external_multiple_structure(
                new external_single_structure([
                    "id" => new external_value(PARAM_INT, "id"),
                    "name" => new external_value(PARAM_TEXT, "name"),
                    "description" => new external_value(PARAM_RAW, "description"),
                    "type" => new external_value(PARAM_TEXT, "type"),
                    "format" => new external_value(PARAM_TEXT, "format"),
                    "path" => new external_value(PARAM_TEXT, "path"),
                    "lang" => new external_value(PARAM_TEXT, "lang"),
                    "userid" => new external_value(PARAM_INT, "userid"),
                    "timecreated" => new external_value(PARAM_INT, "timecreated"),
                    "timemodified" => new external_value(PARAM_INT, "timemodified"),
                    "comment" => new external_value(PARAM_TEXT, "comment"),
                    "themes" => new external_multiple_structure(
                        new external_single_structure([
                            "name" => new external_value(PARAM_TEXT, "name"),
                            "id" => new external_value(PARAM_TEXT),
                        ])
                    ),
                    "tags" => new external_multiple_structure(
                        new external_single_structure([
                            "name" => new external_value(PARAM_TEXT, "name"),
                            "id" => new external_value(PARAM_TEXT),
                        ]), "tags", VALUE_OPTIONAL
                    ),
                    "fixed_tags" => new external_multiple_structure(
                        new external_single_structure([
                            "name" => new external_value(PARAM_TEXT, "name")
                        ]), "fixed_tags", VALUE_OPTIONAL
                    ),
                    "reactions" => new external_single_structure([
                        "likes" => new external_single_structure([
                            "count" => new external_value(PARAM_INT, 'likes count'),
                            "data" => new external_multiple_structure(new external_single_structure([])),
                            "isactive" => new external_value(PARAM_BOOL, 'is active'),
                        ]),
                        "dislikes" => new external_single_structure([
                            "count" => new external_value(PARAM_INT, 'likes count'),
                            "data" => new external_multiple_structure(new external_single_structure([])),
                            "isactive" => new external_value(PARAM_BOOL, 'is active'),
                        ]),
                        "comments" => new external_single_structure([
                            "count" => new external_value(PARAM_INT, 'likes count'),
                            "data" => new external_multiple_structure(new external_single_structure([])),
                        ]),
                        "reports" => new external_single_structure([
                            "count" => new external_value(PARAM_INT, 'likes count'),
                            "data" => new external_multiple_structure(new external_single_structure([])),
                            "isactive" => new external_value(PARAM_BOOL, 'is active'),
                        ]),
                    ]),
                    "type_simplified" => new external_value(PARAM_TEXT, "type_simplified"),
                ])
            ),
            'pages' => new external_value(PARAM_INT, 'pages'),
            'pagenumber' => new external_value(PARAM_INT, 'pagenumber')
        ]);
    }
}