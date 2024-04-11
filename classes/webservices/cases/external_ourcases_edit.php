<?php

/**
 * external_external_ourcases_edit
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


class external_ourcases_edit extends external_api
{

    public static function ourcases_edit_parameters()
    {
        return new external_function_parameters(
            array(
                'ourcaseid' => new external_value(PARAM_INT, 'Our Case ID'),
                'experienceid ' => new external_value(PARAM_INT, 'Experience ID', VALUE_DEFAULT),
                'userid' => new external_value(PARAM_INT, 'User ID', VALUE_DEFAULT),
                'date' => new external_value(PARAM_INT, 'Date', VALUE_DEFAULT),
                'status' => new external_value(PARAM_INT, 'Status', VALUE_DEFAULT)
            )
        );
    }

    public static function ourcases_edit($ourcaseid, $experienceid = null, $userid = null, $timecreated = null, $status = null)
    {
        global $DB;

        if (!$ourcase = $DB->get_record('digital_ourcases', array('id' => $ourcaseid))) {
            return array('result' => false, 'error' => 'Our case not found');
        }

        // Actualizar propiedades del ourcase si se proporcionan nuevos valores
        if ($experienceid !== null) {
            $ourcase->experienceid = $experienceid;
        }
        if ($userid !== null) {
            $ourcase->userid = $userid;
        }
        if ($timecreated !== null) {
            $ourcase->timecreated = $timecreated;
        }
        if ($status !== null) {
            $ourcase->status = $status;
        }

        // Actualizar el registro en la base de datos
        if (!$DB->update_record('digital_ourcases', $ourcase)) {
            return array('result' => false, 'error' => 'Failed to update our case');
        }

        return array('result' => true);
    }

    public static function ourcases_edit_returns()
    {
        return new external_single_structure(
            array(
                'error' => new external_value(PARAM_TEXT, 'Error message if any' , VALUE_OPTIONAL),
                'result' => new external_value(PARAM_BOOL, 'True if success, false otherwise' , VALUE_OPTIONAL)
            )
        );
    }
}
