<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/digitalta/classes/case.php');
require_once($CFG->dirroot . '/local/digitalta/classes/cases.php');
require_once($CFG->dirroot . '/local/digitalta/classes/utils/filterutils.php');
require_login();

use local_digitalta\utils\FilterUtils;

class external_cases_get_by_pagination extends external_api
{
    public static function cases_get_by_pagination_parameters()
    {
        return new external_function_parameters(
            [
                'pagenumber' => new external_value(PARAM_INT, 'page number', VALUE_REQUIRED),
                'filters' => new external_value(PARAM_TEXT, 'filters', VALUE_REQUIRED)
            ]
        );
    }

    public static function cases_get_by_pagination($pagenumber, $filters)
    {
        global $DB, $CFG;

        $limit = 20;
        $totalPages = 0;

        $filters = json_decode($filters, true);

        $cases = array();

        if (count($filters) > 0) {
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
                        WHERE component = 2
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

            if (count($componentInstanceIds) > 0 || count($authors) > 0 || count($langs) > 0) {

                $sqlComponent = 'SELECT * FROM mdl_digitalta_cases WHERE ';
                $sqlTotalRows = 'SELECT COUNT(*) AS total  FROM mdl_digitalta_cases WHERE ';

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

            $cases = self::get_cases($components);
        } else {
            
            $components = array_values(
                $DB->get_records_sql(
                    "SELECT * FROM mdl_digitalta_cases ORDER BY timecreated DESC LIMIT " . $limit . " OFFSET " . (($pagenumber - 1) * $limit)
                )
            );

            $totalRows = $DB->count_records('digitalta_cases');
            $totalPages = ceil($totalRows / $limit);
            $cases = self::get_cases($components);
        }

        return array(
            'component' => 'case',
            'data' => $cases,
            'pages' => $totalPages,
            'pagenumber' => $pagenumber,
            'viewurl' => $CFG->wwwroot . '/local/digitalta/pages/cases/view.php?id=',
            'manageurl' => $CFG->wwwroot . '/local/digitalta/pages/cases/manage.php?id='
        );
    }

    public static function get_cases($components)
    {
        $cases = array();
        for ($i = 0; $i < count($components); $i++) {
            $case = $components[$i];
            $case_model = new \local_digitalta\StudyCase($case);
            $x = \local_digitalta\Cases::get_extra_fields($case_model);
            $x->title = FilterUtils::apply_filters($x->title);
            $x->description = FilterUtils::apply_filters($x->description);


            $sections = [];

            foreach ($x->sections as $section) {
                $groupname = \local_digitalta\Sections::get_group($section->groupid)->name;
                list($section->groupname, $section->groupname_simplified) =
                    local_digitalta_get_element_translation('section_group', $groupname);
                $section->content = strip_tags($section->content);
                $sections[$section->groupname_simplified] = $section;
            }

            $x->sections = $sections;
            $cases[] = $x;
        }
        return $cases;
    }

    public static function cases_get_by_pagination_returns()
    {
        return new external_single_structure([
            'component' => new external_value(PARAM_TEXT, 'component'),
            'pagenumber' => new external_value(PARAM_INT, 'page number'),
            'data' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'case id'),
                    'experienceid' => new external_value(PARAM_INT, 'experience id', VALUE_DEFAULT, 0),
                    'resourceid' => new external_value(PARAM_INT, 'resource id'),
                    'userid' => new external_value(PARAM_INT, 'user id'),
                    "title" => new external_value(PARAM_TEXT, 'title'),
                    "description" => new external_value(PARAM_RAW, 'description'),
                    "lang" => new external_value(PARAM_TEXT, 'language'),
                    "status" => new external_value(PARAM_INT, 'status'),
                    "timecreated" => new external_value(PARAM_INT, 'time created'),
                    "timecreated_string" => new external_value(PARAM_TEXT, 'time created string'),
                    "timemodified" => new external_value(PARAM_INT, 'time modified'),
                    "sections" => new external_multiple_structure(
                        new external_single_structure([
                            "id" => new external_value(PARAM_INT),
                            "component" => new external_value(PARAM_TEXT),
                            "componentinstance" => new external_value(PARAM_TEXT),
                            "groupid" => new external_value(PARAM_TEXT),
                            "sequence" => new external_value(PARAM_TEXT),
                            "type" => new external_value(PARAM_TEXT),
                            "title" => new external_value(PARAM_TEXT),
                            "content" => new external_value(PARAM_TEXT),
                            "groupname" => new external_value(PARAM_TEXT),
                            "groupname_simplified" => new external_value(PARAM_TEXT),
                        ])
                    ),
                    "themes" => new external_multiple_structure(
                        new external_single_structure([
                            "name" => new external_value(PARAM_TEXT),
                            "id" => new external_value(PARAM_TEXT),
                        ])
                    ),
                    "tags" => new external_multiple_structure(
                        new external_single_structure([
                            "name" => new external_value(PARAM_TEXT),
                            "id" => new external_value(PARAM_TEXT),
                        ]), 'tags', VALUE_OPTIONAL
                    ),
                    "fixed_tags" => new external_multiple_structure(
                        new external_single_structure([
                            "name" => new external_value(PARAM_TEXT),
                        ]), 'fixed tags', VALUE_OPTIONAL
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
                    "user" => new external_single_structure([
                        "id" => new external_value(PARAM_INT, 'user id'),
                        "name" => new external_value(PARAM_TEXT, 'user name'),
                        "email" => new external_value(PARAM_TEXT, 'user email'),
                        "imageurl" => new external_value(PARAM_TEXT, 'user image url'),
                        "profileurl" => new external_value(PARAM_URL, 'user profile url'),
                    ])
                ])
            ),
            'pages' => new external_value(PARAM_INT, 'total pages'),
            'viewurl' => new external_value(PARAM_TEXT, 'view url'),
            'manageurl' => new external_value(PARAM_TEXT, 'manage url')
        ]);
    }
}