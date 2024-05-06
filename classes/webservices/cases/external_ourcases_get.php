<?php

/**
 * external_ourcases_section_text_update
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/ourcases.php');

use local_dta\OurCases;

class external_ourcases_get extends external_api
{

    public static function ourcases_get_parameters()
    {
        return new external_function_parameters(
            []
        );
    }

    public static function ourcases_get()
    {
        $cases = OurCases::get_cases(false);
        foreach ($cases as $case) {
            $headerSection = OurCases::get_section_header($case->id);
            if ($headerSection) {
                $case->title = $headerSection->title;
                $case->description = $headerSection->description;
            } else {
                $case->title = null;
                $case->description = null;
            }
        }
        return [
            'status' => true,
            'cases' => $cases
        ];
    }
    public static function ourcases_get_returns()
    {
        return new external_single_structure(
            [
                'status' => new external_value(PARAM_BOOL, 'status'),
                'error' => new external_value(PARAM_TEXT, 'error' , VALUE_OPTIONAL),
                'cases' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'id' => new external_value(PARAM_INT, 'id'),
                            'experienceid' => new external_value(PARAM_INT, 'experienceid'),
                            'userid' => new external_value(PARAM_INT, 'userid'),
                            'status' => new external_value(PARAM_INT, 'status'),
                            'timecreated' => new external_value(PARAM_TEXT, 'timecreated'),
                            'timemodified' => new external_value(PARAM_TEXT, 'timemodified'),
                            'title' => new external_value(PARAM_TEXT, 'title'),
                            'description' => new external_value(PARAM_RAW, 'description')
                        ]
                    )
                )
            ]
        );
    }
}
