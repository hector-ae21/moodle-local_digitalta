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
 * WebService to upsert an experience
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/experiences.php');

use local_dta\Experiences;

/**
 * This class is used to upsert an experience
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_experiences_upsert extends external_api
{

    public static function experiences_upsert_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'experience ID'),
                'title' => new external_value(PARAM_RAW, 'Title'),
                'lang' => new external_value(PARAM_RAW, 'Lang'),
                'visible' => new external_value(PARAM_BOOL, 'Visible', VALUE_DEFAULT, 1),
                'status' => new external_value(PARAM_INT, 'Status' , VALUE_DEFAULT, 0),
                'themes' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'ID del elemento')  , 'Themes' , VALUE_DEFAULT, []
                ),
                'tags' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'ID del elemento')  , 'Tags' , VALUE_DEFAULT, []
                )
            )
        );
    }

    public static function experiences_upsert($id, $title, $lang, $visible = 1, $status = 0 , $themes = [], $tags = [])
    {
        $experience          = new stdClass();
        $experience->id      = $id ?? null;
        $experience->title   = $title;
        $experience->lang    = $lang;
        $experience->visible = $visible;
        $experience->status  = $status;
        $experience->themes  = $themes;
        $experience->tags    = $tags;

        if(!$experience = Experiences::upsert_experience($experience)){
            return [
                'result' => false,
                'error' => 'Error saving experience'
            ];
        }

        return [
            'result' => true,
            'experienceid' => $experience->id
        ];
    }

    public static function experiences_upsert_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'experienceid' => new external_value(PARAM_INT, 'Section ID' , VALUE_OPTIONAL),
                'error' => new external_value(PARAM_RAW, 'Error message' , VALUE_OPTIONAL),
            ]
        );
    }
}
