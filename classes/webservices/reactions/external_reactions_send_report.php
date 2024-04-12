<?php

/**
 * External Web Service
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class external_reactions_send_report extends external_api
{

    public static function reactions_send_report_parameters()
    {
        return new external_function_parameters(
            array(
                'instancetype' => new external_value(PARAM_INT, 'Type of instance 1 for experiences, 0 for cases', VALUE_REQUIRED),
                'instanceid' => new external_value(PARAM_INT, 'ID of the instance', VALUE_REQUIRED)
            )
        );
    }

    public static function reactions_send_report($type, $id)
    {
        global $USER, $DB;

        if (!$USER->id) {
            return ['result' => false, 'error' => 'User not logged in'];
        }

        if (!self::validate_instance_id($type, $id)) {
            return ['result' => false, 'error' => 'Invalid instance id'];
        }

        $table = self::get_table($type);
        $column = self::get_column($type);

        $report = new \stdClass();
        $report->userid = $USER->id;
        $report->$column = $id;

        $DB->insert_record($table, $report);

        return ['result' => true];
    }

    protected static function validate_instance_id($type, $instanceid)
    {
        global $DB;

        if ($type != 0 && $type != 1) {
            return false;
        }

        if ($type == 0) {
            return $DB->get_record('digital_ourcases', array('id' => $instanceid));
        } else {
            return $DB->get_record('digital_experiences', array('id' => $instanceid));
        }
    }

    protected static function get_table($type)
    {
        switch ($type) {
            case 0:
                return 'digital_case_report';
            case 1:
                return 'digital_experience_report';
            default:
                return null;
        }
    }

    protected static function get_column($type)
    {
        switch ($type) {
            case 0:
                return 'caseid';
            case 1:
                return 'experienceid';
            default:
                return null;
        }
    }

    public static function reactions_send_report_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result of the operation')
            ]
        );
    }
}
