<?php

/**
 * external_myexperience_save
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../../experience.php');


use local_dta\Experience;

class external_myexperience_toggle_status extends external_api
{

    public static function myexperience_toggle_status_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'experience ID'),
            )
        );
    }

    public static function myexperience_toggle_status($id)
    {
        return [
            'result' => true,
            'status' => Experience::change_status_experience($id)->status,
        ];
    }

    public static function myexperience_toggle_status_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'status' => new external_value(PARAM_INT, 'Section ID'),
            ]
        );
    }
}
