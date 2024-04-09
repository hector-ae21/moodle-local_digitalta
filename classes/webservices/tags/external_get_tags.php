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

class external_create_tags extends external_api
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
        return $cases;
    }

    public static function ourcases_get_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'ID of the ourcase'),
                    'experienceid' => new external_value(PARAM_INT, 'ID of the experience', VALUE_OPTIONAL),
                    'userid' => new external_value(PARAM_INT, 'ID of the user'),
                    'date' => new external_value(PARAM_TEXT, 'Experience date'),
                    'status' => new external_value(PARAM_INT, 'Status of the ourcase'),
                    'title' => new external_value(PARAM_RAW, 'Title of the header section', VALUE_OPTIONAL),
                    'description' => new external_value(PARAM_RAW, 'Description of the header section', VALUE_OPTIONAL)
                )
            )
        );
    }
}
