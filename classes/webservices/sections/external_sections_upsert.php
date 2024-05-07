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
 * WebService to upsert a section
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/sections.php');

use local_dta\Components;
use local_dta\Sections;

use stdClass;

/**
 * This class is used to upsert a section
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_sections_upsert extends external_api
{
 
    /**
     * Returns the description of the external function parameters
     *
     * @return external_function_parameters The external function parameters
     */
    public static function sections_upsert_parameters()
    {
        return new external_function_parameters(
            [
                'sectionid' => new external_value(PARAM_INT, 'Section ID', VALUE_DEFAULT, 0),
                'sectiontype' => new external_value(PARAM_TEXT, 'Section type', VALUE_DEFAULT, 'text'),
                'component' => new external_value(PARAM_TEXT, 'Component'),
                'componentinstance' => new external_value(PARAM_INT, 'Component instance'),
                'group' => new external_value(PARAM_TEXT, 'Group ID', VALUE_DEFAULT, 'General'),
                'sequence' => new external_value(PARAM_INT, 'Sequence', VALUE_DEFAULT, 0),
                'content' => new external_value(PARAM_TEXT, 'Content', VALUE_DEFAULT, "")
            ]
        );
    }

    /**
     * Upserts a section
     *
     * @param  int    $sectionid         Section ID
     * @param  string $sectiontype       Section type
     * @param  string $component         Component
     * @param  int    $componentinstance Component instance
     * @param  string $group             Group ID
     * @param  int    $sequence          Sequence
     * @param  string $content           Content
     * @return array  The result of the operation
     */
    public static function sections_upsert($sectionid = 0, $sectiontype = 'text', $component, $componentinstance, $group = 'General', $sequence = 0, $content = "")
    {
        $section = new stdClass();

        if ($sectionid > 0) {
            if (!$current_section = Sections::get_section($sectionid)) {
                return ['result' => false, 'error' => 'Section not found'];
            }
            $section->id = $sectionid;
        }

        $section->sectiontype = Sections::get_type_by_name($sectiontype)->id;

        if (!Components::get_instance_record($component, $componentinstance)) {
            return ['result' => false, 'error' => 'Component instance not found'];
        }

        $section->component         = Components::get_component_by_name($component)->id;
        $section->componentinstance = $componentinstance;
        $section->groupid           = Sections::get_group_by_name($group)->id;

        if ($sequence !== -1) {
            $section->sequence = $sequence;
        }

        $section->content = $content;
        

        if (!$result = Sections::upsert_section($section)) {
            return ['result' => false, 'error' => 'Error upserting section'];
        }
        return [
            'result' => true,
            'sectionid' => $result->id,
            'sectiontype' => $sectiontype,
            'component' => $component,
            'componentinstance' => $componentinstance,
            'group' => $group,
            'sequence' => $sequence,
            'content' => $content
        ];
    }

    /**
     * Returns the description of the external function returns
     *
     * @return external_single_structure The external function returns
     */
    public static function sections_upsert_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'sectionid' => new external_value(PARAM_INT, 'Section ID', VALUE_OPTIONAL),
                'sectiontype' => new external_value(PARAM_TEXT, 'Section type', VALUE_OPTIONAL),
                'component' => new external_value(PARAM_TEXT, 'Component', VALUE_OPTIONAL),
                'componentinstance' => new external_value(PARAM_INT, 'Component instance', VALUE_OPTIONAL),
                'group' => new external_value(PARAM_TEXT, 'Group ID', VALUE_OPTIONAL),
                'sequence' => new external_value(PARAM_INT, 'Sequence', VALUE_OPTIONAL),
                'content' => new external_value(PARAM_TEXT, 'Content', VALUE_OPTIONAL),
                'error' => new external_value(PARAM_RAW, 'Error', VALUE_OPTIONAL)
            ]
        );
    }
}
