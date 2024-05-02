<?php

/**
 * external_create_tutor_disponibility
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/tutor_disponibility.php');

use local_dta\tutor_disponibility;

class external_delete_tutor_disponibility extends external_api
{

    public static function delete_tutor_disponibility_parameters()
    {
        return new external_function_parameters(
            [
                'id' => new external_value(PARAM_INT, 'Tutor disponibility id', VALUE_REQUIRED)
            ]
        );
    }

    public static function delete_tutor_disponibility($userid, $day, $timefrom, $timeto)
    {
        try {
            $tutor_disponibility = tutor_disponibility::delete($userid, $day, $timefrom, $timeto);
            if (!$tutor_disponibility) {
                return false;
            }
        } catch (\Exception $e) {
            throw new moodle_exception('error_creating_tutor_disponibility', 'local_dta', null, $e->getMessage());
        }
        return true;
    }

    public static function delete_tutor_disponibility_returns()
    {
        return new external_value(PARAM_BOOL, 'True if the tutor disponibility was deleted');
    }
}
