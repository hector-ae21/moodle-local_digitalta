<?php

/**
 * external_ourcases_section_text_update
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

class local_dta_external_ourcases_section_text_upsert extends external_api
{

    public static function ourcases_section_text_upsert_parameters()
    {
        return new external_function_parameters(
            array(
                'ourcaseid' => new external_value(PARAM_INT, 'Our Case ID'),
                'sectionid' => new external_value(PARAM_INT, 'Section ID', VALUE_OPTIONAL),
                'title' => new external_value(PARAM_RAW, 'Title' , VALUE_OPTIONAL),
                'text' => new external_value(PARAM_RAW, 'Text' , VALUE_OPTIONAL),
                'sequence' => new external_value(PARAM_INT, 'Sequence', VALUE_OPTIONAL)
            )
        );
    }

    public static function ourcases_section_text_upsert($ourcaseid, $sectionid = 0, $title = "", $text = "", $sequence = 0)
    {
        global $DB;

        if ($DB->get_record('digital_ourcases', array('id' => $ourcaseid))) {
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
        $section->text = $text;
        $section->sequence = $sequence;

        $DB->update_record('digital_oc_sec_text', $section);

        return array('result' => true, 'sectionid' => $sectionid , 'title' => $title, 'text' => $text, 'sequence' => $sequence);
    }

    public static function ourcases_section_text_upsert_returns()
    {
        return new external_single_structure(
            array(
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'sectionid' => new external_value(PARAM_INT, 'Section ID'),
                'title' => new external_value(PARAM_RAW, 'Title'),
                'text' => new external_value(PARAM_RAW, 'Text'),
                'sequence' => new external_value(PARAM_INT, 'Sequence')
            )
        );
    }
}
