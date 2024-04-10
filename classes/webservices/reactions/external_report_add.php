<?php

/**
 * External Web Service
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class external_report_add extends external_api
{

    public static function report_add_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'ID of the instance (CASE OR EXPERIENCE) id', VALUE_REQUIRED),
                'type' => new external_value(PARAM_INT, 'Type of reaction 1 for experiences, 0 for cases', VALUE_REQUIRED)
            )
        );
    }

    public static function report_add($id = 1, $action = null)
    {
        global $USER, $DB;

        $reaction = new \stdClass();

        if($type == 0){
            if (!$DB->get_record('digital_ourcases', array('id' => $id))) {
                return array('result' => false, 'error' => 'Case not found');
            }

            $reaction->caseid = $id;
            $reaction->userid = $USER->id;

            $table = 'digital_case_report';
            $column = 'caseid';   
        }else{
            if (!$DB->get_record('digital_experiences', array('id' => $id))) {
                return array('result' => false, 'error' => 'Experience not found');
            }

            $reaction->experienceid = $id;
            $reaction->userid = $USER->id;
            $reaction->reactiontype = $action;

            $table = 'digital_experience_report';
            $column = 'experienceid';   
        }


        if ($actual_reaction = $DB->get_record($table, array($column => $id, 'userid' => $USER->id))) {
            $reaction->id = $actual_reaction->id;
            $DB->update_record($table, $reaction);
        } else {
            $DB->insert_record($table, $reaction);
        }


        $report = $DB->count_records($table, array($column => $id));

        return ['result' => true, 'report' => $report];
    }

    public static function report_add_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result of the operation'),
                'report' => new external_value(PARAM_INT, 'Number of likes')
            ]
        );
    }
}
