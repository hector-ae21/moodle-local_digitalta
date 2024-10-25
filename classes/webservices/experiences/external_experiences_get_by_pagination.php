<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/digitalta/classes/experiences.php');
require_once($CFG->dirroot . '/local/digitalta/classes/sections.php');
require_once($CFG->dirroot . '/config.php');
require_login();


class external_experiences_get_by_pagination extends external_api
{
    public static function experiences_get_by_pagination_parameters()
    {
        return new external_function_parameters(
            [
                'pagenumber' => new external_value(PARAM_INT, 'page number', VALUE_REQUIRED),
                'filters' => new external_value(PARAM_TEXT, 'filters', VALUE_REQUIRED)
            ]
        );
    }

    public static function experiences_get_by_pagination($pagenumber, $filters)
    {
        global $DB, $CFG, $PAGE;

        $PAGE->set_context(\context_system::instance());

        $limit = 20;
        $totalPages = 0;

        $filters = json_decode($filters, true);

        $experiences = array();

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
                if (strlen($havingSum) == 0) {
                    $havingSum .= "HAVING";
                }
                $havingSum .= " (SUM(CASE WHEN modifier = 1 AND modifierinstance = " . $theme . " THEN 1 ELSE 0 END) > 0) OR ";
            }

            if (count($tags) > 0) {
                $tagsToSearch = '(' . implode(', ', $tags) . ')';
                $tagsExperiences = $DB->get_records_sql(
                    "SELECT * FROM mdl_digitalta_tags where name IN " . $tagsToSearch
                );
                $tagsId = array_keys($tagsExperiences);
                foreach ($tagsId as $tagId) {
                    if (strlen($havingSum) == 0) {
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
                        WHERE component = 1
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

                $sqlComponent = 'SELECT * FROM mdl_digitalta_experiences WHERE ';
                $sqlTotalRows = 'SELECT COUNT(*) AS total  FROM mdl_digitalta_experiences WHERE ';

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

            $experiences = self::getExperiences($components);
        } else {

            $components = array_values(
                $DB->get_records_sql(
                    "SELECT * FROM mdl_digitalta_experiences ORDER BY timecreated DESC LIMIT " . $limit . " OFFSET " . (($pagenumber - 1) * $limit)
                )
            );

            $totalRows = $DB->count_records('digitalta_experiences');
            $totalPages = ceil($totalRows / $limit);
            $experiences = self::getExperiences($components);
        }

        return array(
            'component' => 'experience',
            'data' => $experiences,
            'pages' => $totalPages,
            'pagenumber' => $pagenumber,
            'viewurl' => $CFG->wwwroot . '/local/digitalta/pages/experiences/view.php?id='
        );
    }

    public static function getExperiences(array $components)
    {
        global $USER;
        $experiences = array();
        for ($i = 0; $i < count($components); $i++) {
            $experience = $components[$i];
            $cleaned_experience = [
                "id" => $experience->id,
                "userid" => $experience->userid,
                "title" => $experience->title,
                "lang" => $experience->lang,
                "visible" => $experience->visible,
                "status" => $experience->status,
                "timecreated" => $experience->timecreated,
                "timemodified" => $experience->timemodified
            ];
            $experience_model = new \local_digitalta\Experience($cleaned_experience);
            $x = \local_digitalta\Experiences::get_extra_fields($experience_model);

            $sections = [];
            foreach ($x->sections as $section) {
                $groupname = \local_digitalta\Sections::get_group($section->groupid)->name;
                list($section->groupname, $section->groupname_simplified) =
                    local_digitalta_get_element_translation('section_group', $groupname);
                $section->content = strip_tags($section->content);
                $sections[$section->groupname_simplified] = $section;
            }

            $x->sections = $sections;
            $experiences[] = $x;
        }
        $experiences = array_filter($experiences, function ($experience) use ($USER) {
            return $experience->visible == 1 || ($experience->userid == $USER->id);
        });
        return $experiences;
    }

    public static function experiences_get_by_pagination_returns()
    {
        return new external_single_structure([
            'component' => new external_value(PARAM_TEXT, 'component', VALUE_REQUIRED),
            'pagenumber' => new external_value(PARAM_INT, 'page number', VALUE_REQUIRED),
            'data' => new external_multiple_structure(
                new external_single_structure([
                    "id" => new external_value(PARAM_INT, 'id', VALUE_REQUIRED),
                    "userid" => new external_value(PARAM_INT, 'userid', VALUE_REQUIRED),
                    "user" => new external_single_structure([
                        "id" => new external_value(PARAM_INT, 'user', VALUE_REQUIRED),
                        "name" => new external_value(PARAM_TEXT, 'name', VALUE_REQUIRED),
                        "email" => new external_value(PARAM_TEXT, 'email', VALUE_REQUIRED),
                        "imageurl" => new external_value(PARAM_URL, 'image url', VALUE_REQUIRED),
                        "profileurl" => new external_value(PARAM_URL, 'profile url', VALUE_REQUIRED)
                    ]),
                    "title" => new external_value(PARAM_TEXT, 'title', VALUE_REQUIRED),
                    "lang" => new external_value(PARAM_TEXT, 'lang', VALUE_REQUIRED),
                    "visible" => new external_value(PARAM_INT, 'visible', VALUE_REQUIRED),
                    "status" => new external_value(PARAM_INT, 'status', VALUE_REQUIRED),
                    "timecreated" => new external_value(PARAM_TEXT, 'timecreated', VALUE_REQUIRED),
                    "timecreated_string" => new external_value(PARAM_TEXT, 'timecreated_string', VALUE_REQUIRED),
                    "timemodified" => new external_value(PARAM_TEXT, 'timemodified', VALUE_REQUIRED),
                    'sections' => new external_single_structure([
                        'what' => new external_single_structure([
                            'id' => new external_value(PARAM_TEXT, 'ID of the what section', VALUE_REQUIRED),
                            'component' => new external_value(PARAM_TEXT, 'Component of the what section', VALUE_REQUIRED),
                            'componentinstance' => new external_value(PARAM_TEXT, 'Component instance of the what section', VALUE_REQUIRED),
                            'groupid' => new external_value(PARAM_TEXT, 'Group ID of the what section', VALUE_REQUIRED),
                            'sequence' => new external_value(PARAM_TEXT, 'Sequence of the what section', VALUE_REQUIRED),
                            'type' => new external_value(PARAM_TEXT, 'Type of the what section', VALUE_REQUIRED),
                            'content' => new external_value(PARAM_RAW, 'Content of the what section', VALUE_REQUIRED),
                            'groupname' => new external_value(PARAM_TEXT, 'Group name of the what section', VALUE_REQUIRED),
                            'groupname_simplified' => new external_value(PARAM_TEXT, 'Simplified group name of the what section', VALUE_REQUIRED)
                        ]),
                        'so_what' => new external_single_structure([
                            'id' => new external_value(PARAM_TEXT, 'ID of the so_what section', VALUE_REQUIRED),
                            'component' => new external_value(PARAM_TEXT, 'Component of the so_what section', VALUE_REQUIRED),
                            'componentinstance' => new external_value(PARAM_TEXT, 'Component instance of the so_what section', VALUE_REQUIRED),
                            'groupid' => new external_value(PARAM_TEXT, 'Group ID of the so_what section', VALUE_REQUIRED),
                            'sequence' => new external_value(PARAM_TEXT, 'Sequence of the so_what section', VALUE_REQUIRED),
                            'type' => new external_value(PARAM_TEXT, 'Type of the so_what section', VALUE_REQUIRED),
                            'content' => new external_value(PARAM_RAW, 'Content of the so_what section', VALUE_REQUIRED),
                            'groupname' => new external_value(PARAM_TEXT, 'Group name of the so_what section', VALUE_REQUIRED),
                            'groupname_simplified' => new external_value(PARAM_TEXT, 'Simplified group name of the so_what section', VALUE_REQUIRED)
                        ]),
                        'now_what' => new external_single_structure([
                            'id' => new external_value(PARAM_TEXT, 'ID of the now_what section', VALUE_REQUIRED),
                            'component' => new external_value(PARAM_TEXT, 'Component of the now_what section', VALUE_REQUIRED),
                            'componentinstance' => new external_value(PARAM_TEXT, 'Component instance of the now_what section', VALUE_REQUIRED),
                            'groupid' => new external_value(PARAM_TEXT, 'Group ID of the now_what section', VALUE_REQUIRED),
                            'sequence' => new external_value(PARAM_TEXT, 'Sequence of the now_what section', VALUE_REQUIRED),
                            'type' => new external_value(PARAM_TEXT, 'Type of the now_what section', VALUE_REQUIRED),
                            'content' => new external_value(PARAM_RAW, 'Content of the now_what section', VALUE_REQUIRED),
                            'groupname' => new external_value(PARAM_TEXT, 'Group name of the now_what section', VALUE_REQUIRED),
                            'groupname_simplified' => new external_value(PARAM_TEXT, 'Simplified group name of the now_what section', VALUE_REQUIRED)
                        ])
                    ]),
                    "pictureurl" => new external_value(PARAM_TEXT, 'picture url', VALUE_REQUIRED),
                    "themes" => new external_multiple_structure(
                        new external_single_structure([
                            'id' => new external_value(PARAM_TEXT, 'id', VALUE_REQUIRED),
                            'name' => new external_value(PARAM_TEXT, 'name', VALUE_REQUIRED),
                        ])
                    ),
                    "tags" => new external_multiple_structure(
                        new external_single_structure([
                            'id' => new external_value(PARAM_TEXT, 'id', VALUE_REQUIRED),
                            'name' => new external_value(PARAM_TEXT, 'name', VALUE_REQUIRED),
                        ])
                    ),
                    "fixed_tags" => new external_multiple_structure(
                        new external_single_structure([
                            'name' => new external_value(PARAM_TEXT, 'name', VALUE_REQUIRED),
                        ])
                    ),
                    "reactions" => new external_single_structure([
                        "likes" => new external_single_structure([
                            "count" => new external_value(PARAM_TEXT, 'likes', VALUE_REQUIRED),
                            "isactive" => new external_value(PARAM_RAW, ''),
                            "data" => new external_multiple_structure(
                                new external_single_structure([])
                            )
                        ]),
                        "dislikes" => new external_single_structure([
                            "count" => new external_value(PARAM_TEXT, 'likes', VALUE_REQUIRED),
                            "isactive" => new external_value(PARAM_RAW, ''),
                            "data" => new external_multiple_structure(
                                new external_single_structure([])
                            )
                        ]),
                        "comments" => new external_single_structure([
                            "count" => new external_value(PARAM_TEXT, 'likes', VALUE_REQUIRED),
                            "data" => new external_multiple_structure(
                                new external_single_structure([])
                            )
                        ]),
                        "reports" => new external_single_structure([
                            "count" => new external_value(PARAM_TEXT, 'likes', VALUE_REQUIRED),
                            "isactive" => new external_value(PARAM_RAW, ''),
                            "data" => new external_multiple_structure(
                                new external_single_structure([])
                            )
                        ])
                    ])
                ])
            ),
            'pages' => new external_value(PARAM_INT, 'pages', VALUE_REQUIRED),
            "viewurl" => new external_value(PARAM_TEXT, 'view url', VALUE_REQUIRED)
        ]);
    }
}
