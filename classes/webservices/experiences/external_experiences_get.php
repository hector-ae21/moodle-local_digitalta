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
 * WebService to get an experience
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/experiences.php');
require_once($CFG->dirroot . '/local/dta/classes/sections.php');
require_once($CFG->dirroot . '/local/dta/classes/tags.php');
require_once($CFG->dirroot . '/local/dta/classes/themes.php');

use local_dta\Components;
use local_dta\Experiences;
use local_dta\Sections;
use local_dta\Tags;
use local_dta\Themes;

/**
 * This class is used to get an experience
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_experiences_get extends external_api
{
    /**
     * Returns the description of the external function parameters
     *
     * @return external_function_parameters The external function parameters
     */
    public static function experiences_get_parameters()
    {
        return new external_function_parameters(
            [
                'id' => new external_value(PARAM_INT, 'experience ID'),
            ]
        );
    }

    /**
     * Gets an experience
     *
     * @param  int   $id Experience ID
     * @return array The result of the operation
     */
    public static function experiences_get($id)
    {
        // Check if the experience exists
        if (!$experience = Experiences::get_experience($id, false)) {
            return [
                'result' => false,
                'error' => 'Error getting experience'
            ];
        }

        // Get the description for the experience
        $section = Sections::get_sections([
            'component' => [Components::get_component_by_name('experience')->id],
            'componentinstance' => [$experience->id],
            'groupid' => [Sections::get_group_by_name('What?')->id]
        ]);
        $section = reset($section);
        $experience->description = $section->content;

        // Get the themes for the experience
        $themes = Themes::get_themes_for_component('experience', $experience->id);
        $experience->themes = array_values(array_map(function ($theme) {
            return (object) [
                'id' => $theme->id,
                'name' => $theme->name
            ];
        }, $themes));

        // Get the tags for the experience
        $tags = Tags::get_tags_for_component('experience', $experience->id);
        $experience->tags = array_values(array_map(function ($tag) {
            return (object) [
                'id' => $tag->id,
                'name' => $tag->name
            ];
        }, $tags));

        return [
            'result' => true,
            'experience' => $experience
        ];
    }

    /**
     * Returns the description of the external function returns
     *
     * @return external_single_structure The external function returns
     */
    public static function experiences_get_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'experience' => new external_single_structure(
                    [
                        'id' => new external_value(PARAM_INT, 'ID'),
                        'userid' => new external_value(PARAM_INT, 'User ID'),
                        'title' => new external_value(PARAM_TEXT, 'Title'),
                        'description' => new external_value(PARAM_RAW, 'Description'),
                        'lang' => new external_value(PARAM_TEXT, 'Lang'),
                        'visible' => new external_value(PARAM_INT, 'Visible'),
                        'status' => new external_value(PARAM_INT, 'Status'),
                        'timecreated' => new external_value(PARAM_TEXT, 'Time created'),
                        'timemodified' => new external_value(PARAM_TEXT, 'Time modified'),
                        'tags' => new external_multiple_structure(
                            new external_single_structure(
                                [
                                    'id' => new external_value(PARAM_INT, 'ID'),
                                    'name' => new external_value(PARAM_TEXT, 'Name')
                                ]
                            ), 'Tags', VALUE_OPTIONAL
                        ),
                        'themes' => new external_multiple_structure(
                            new external_single_structure(
                                [
                                    'id' => new external_value(PARAM_INT, 'ID'),
                                    'name' => new external_value(PARAM_TEXT, 'Name')
                                ]
                            ), 'themes', VALUE_OPTIONAL
                        ),
                    ], 'Experience', VALUE_OPTIONAL,
                ),
                'error' => new external_value(PARAM_TEXT, 'Error message', VALUE_OPTIONAL)
            ]
        );
    }
}
