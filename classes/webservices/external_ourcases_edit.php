<?php

/**
 * external_external_ourcases_edit
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


class external_external_ourcases_edit extends external_api
{

    public static function external_ourcases_edit_parameters()
    {
        return new external_function_parameters(
            array(
                'ourcaseid' => new external_value(PARAM_INT, 'Our Case ID'),
                'experienceid ' => new external_value(PARAM_INT, 'Experience ID' , VALUE_DEFAULT, 0),
                'userid' => new external_value(PARAM_INT, 'User ID' , VALUE_DEFAULT, 0),
                'date' => new external_value(PARAM_INT, 'Date' , VALUE_DEFAULT, 0),
                'status' => new external_value(PARAM_INT, 'Status' , VALUE_DEFAULT, 0)
            )
        );
    }

    public static function external_ourcases_edit($ourcaseid, $experienceid  = 0, $userid = 0 , $date = 0, $status = 0)
    {
        global $DB;

        if (!$DB->get_record('digital_ourcases', array('id' => $ourcaseid))) {
            return array('result' => false, 'error' => 'Our case not found');
        }

        // if all values are different of 0, then update the value

    }

    public static function external_ourcases_edit_returns()
    {
        return new external_single_structure(
            [

            ]
        );
    }
}
