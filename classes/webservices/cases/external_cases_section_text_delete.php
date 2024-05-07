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
 * WebService to delete a section text
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This class is used to delete a section text
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_cases_section_text_delete extends external_api
{

    public static function cases_section_text_delete_parameters()
    {
        return new external_function_parameters(
            array(
                'caseid' => new external_value(PARAM_INT, 'Case ID'),
                'sectionid' => new external_value(PARAM_INT, 'Section ID')
            )
        );
    }

    public static function cases_section_text_delete($caseid, $sectionid)
    {
        global $DB;

        if (!$DB->get_record('digital_cases', array('id' => $caseid))) {
            return array('result' => false, 'error' => 'Case not found');
        }

        if (!$DB->get_record('digital_oc_sec_text', array('id' => $sectionid))) {
            return array('result' => false, 'error' => 'Section not found');
        }

        $DB->delete_records('digital_oc_sec_text', array('id' => $sectionid));
        
        return array('result' => true);

    }

    public static function cases_section_text_delete_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'true if the section was deleted, false otherwise'),
                'error' => new external_value(PARAM_RAW, 'error message if the section was not deleted' , VALUE_OPTIONAL)
            ]
        );
    }
}
