<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * WebService to upsert a section text
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This class is used to upsert a section text
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_ourcases_section_text_upsert extends external_api
{

    public static function ourcases_section_text_upsert_parameters()
    {
        return new external_function_parameters(
            array(
                'ourcaseid' => new external_value(PARAM_INT, 'Our Case ID'),
                'sectionid' => new external_value(PARAM_INT, 'Section ID', VALUE_DEFAULT, 0),
                'title' => new external_value(PARAM_RAW, 'Title' , VALUE_DEFAULT, "section_body"),
                'text' => new external_value(PARAM_RAW, 'Text' , VALUE_DEFAULT, ""),
                'sequence' => new external_value(PARAM_INT, 'Sequence', VALUE_DEFAULT, 0)
            )
        );
    }

    public static function ourcases_section_text_upsert($ourcaseid, $sectionid = 0, $title = "section_body", $text = "", $sequence = 0)
    {
        global $DB;

        if (!$DB->get_record('digital_cases', array('id' => $ourcaseid))) {
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

    public static function ourcases_section_text_upsert_returns()
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
