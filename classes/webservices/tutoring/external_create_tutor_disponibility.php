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

class external_create_tutor_disponibility extends external_api
{

    public static function create_tutor_disponibility_parameters()
    {
        return new external_function_parameters(
            [
                'userid' => new external_value(PARAM_INT, 'User id', VALUE_REQUIRED),
                'day' => new external_value(PARAM_TEXT, 'Day', VALUE_REQUIRED),
                'timefrom' => new external_value(PARAM_TEXT, 'Time from', VALUE_REQUIRED),
                'timeto' => new external_value(PARAM_TEXT, 'Time to', VALUE_REQUIRED)
            ]
        );
    }

    public static function create_tutor_disponibility($userid, $day, $timefrom, $timeto)
    {
        try {
            $tutor_disponibility = tutor_disponibility::create($userid, $day, $timefrom, $timeto);
        } catch (\Exception $e) {
            throw new moodle_exception('error_creating_tutor_disponibility', 'local_dta', null, $e->getMessage());
        }
        return [
            'id' => $tutor_disponibility->id,
            'userid' => $tutor_disponibility->userid,
            'day' => $tutor_disponibility->day,
            'timefrom' => $tutor_disponibility->timefrom,
            'timeto' => $tutor_disponibility->timeto,
            'timecreated' => $tutor_disponibility->timecreated
        ];
    }

    public static function create_tutor_disponibility_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Tutor disponibility ID'),
                    'userid' => new external_value(PARAM_INT, 'User ID'),
                    'day' => new external_value(PARAM_TEXT, 'Day'),
                    'timefrom' => new external_value(PARAM_TEXT, 'Time from'),
                    'timeto' => new external_value(PARAM_TEXT, 'Time to'),
                    'timecreated' => new external_value(PARAM_INT, 'Time created')
                )
            )
        );
    }
}
