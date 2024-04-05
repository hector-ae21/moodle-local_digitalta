<?php

/**
 * external_myexperience_save
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


class external_myexperience_save extends external_api
{

    public static function myexperience_save_parameters()
    {
        return new external_function_parameters(
            array(
                'title' => new external_value(PARAM_RAW, 'Title' , VALUE_OPTIONAL),
                'description' => new external_value(PARAM_INT, 'Description' VALUE_OPTIONAL),
                'context' => new external_value(PARAM_RAW, 'Context' , VALUE_OPTIONAL),
                'date' => new external_value(PARAM_RAW, 'Date' , VALUE_OPTIONAL),
                'lang' => new external_value(PARAM_RAW, 'Lang' , VALUE_OPTIONAL),
                'userid' => new external_value(PARAM_INT, 'User ID' , VALUE_OPTIONAL),
                'visible' => new external_value(PARAM_BOOL, 'Visible' , VALUE_OPTIONAL),
                'status' => new external_value(PARAM_INT, 'Status' , VALUE_OPTIONAL),
            )
        );
    }

    public static function myexperience_save($ourcaseid, $sectionid = 0, $title = "section_body", $text = "", $sequence = 0)
    {
        global $DB;

        if (!$DB->get_record('digital_ourcases', array('id' => $ourcaseid))) {
            return array('result' => false, 'error' => 'Our case not found');
        }
        

        if ($sectionid) {
            if (!$section = $DB->get_record('digital_oc_sec_text', array('id' => $sectionid))) {
                return array('result' => false, 'error' => 'Section not found');
            }
        } else {
            $section = new \stdClass();
            $section->ourcaseid = $ourcaseid;
            $section->title = $title;
            $section->description = $text;
            $section->sequence = $DB->get_field('digital_oc_sec_text', 'MAX(sequence)', array('ourcaseid' => $ourcaseid)) + 1;
            $section->id = $DB->insert_record('digital_oc_sec_text', $section);
        }

        $section->title = $title;
        $section->description = $text;
        // if -1 will asign the next sequence in db 
        if($sequence ==! -1){
            $section->sequence = $sequence;
        }

        $DB->update_record('digital_oc_sec_text', $section);

        return array('result' => true, 'sectionid' => $section->id , 'title' => $title, 'text' => $text, 'sequence' => $sequence);
    }

    public static function myexperience_save_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'sectionid' => new external_value(PARAM_INT, 'Section ID' , VALUE_OPTIONAL),
                'title' => new external_value(PARAM_RAW, 'Title' , VALUE_OPTIONAL),
                'text' => new external_value(PARAM_RAW, 'Text' , VALUE_OPTIONAL),
                'sequence' => new external_value(PARAM_INT, 'Sequence' , VALUE_OPTIONAL),
                'error' => new external_value(PARAM_RAW, 'Error' , VALUE_OPTIONAL),
            ]
        );
    }
}
