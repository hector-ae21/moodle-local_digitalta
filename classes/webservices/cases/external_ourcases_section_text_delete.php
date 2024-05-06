<?php

/**
 * external_ourcases_section_text_delete
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


class external_ourcases_section_text_delete extends external_api
{

    public static function ourcases_section_text_delete_parameters()
    {
        return new external_function_parameters(
            array(
                'ourcaseid' => new external_value(PARAM_INT, 'Our Case ID'),
                'sectionid' => new external_value(PARAM_INT, 'Section ID')
            )
        );
    }

    public static function ourcases_section_text_delete($ourcaseid, $sectionid)
    {
        global $DB;

        if (!$DB->get_record('digital_cases', array('id' => $ourcaseid))) {
            return array('result' => false, 'error' => 'Our case not found');
        }

        if (!$DB->get_record('digital_oc_sec_text', array('id' => $sectionid))) {
            return array('result' => false, 'error' => 'Section not found');
        }

        $DB->delete_records('digital_oc_sec_text', array('id' => $sectionid));
        
        return array('result' => true);

    }

    public static function ourcases_section_text_delete_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'true if the section was deleted, false otherwise'),
                'error' => new external_value(PARAM_RAW, 'error message if the section was not deleted' , VALUE_OPTIONAL)
            ]
        );
    }
}
