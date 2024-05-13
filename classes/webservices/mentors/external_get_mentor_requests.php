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
 * WebService to get mentors requests
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/tags.php');
require_once($CFG->dirroot . '/local/dta/classes/mentors.php');

use local_dta\Mentor;

/**
 * This class is used to create tags
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_get_mentor_requests extends external_api
{

    public static function get_mentor_requests_parameters()
    {
        return new external_function_parameters(
            [
                'experienceid' => new external_value(PARAM_INT, 'Experience id'),
            ]
        );
    }

    public static function get_mentor_requests($experienceid)
    {
        global $DB, $PAGE;
        $mentors = Mentor::get_mentor_requests_by_experience($experienceid);

        if (count($mentors) == 0) {
            return [
                'total' => 0,
                'requests' => []
            ];
        }

        $mentors = array_map(function ($mentor) use ($DB, $PAGE) {
            $mentor_info = $DB->get_record('user', ['id' => $mentor->mentorid]);
            $mentor_picture = new user_picture($mentor);
            $mentor_picture->size = 101;
            return [
                'id' => $mentor_info->id,
                'firstname' => $mentor_info->firstname,
                'lastname' => $mentor_info->lastname,
                'profileimageurl' => $mentor_picture->get_url($PAGE)->__toString()
            ];
        }, $mentors);
        return [
            'total' => count($mentors),
            'requests' => $mentors
        ];
    }

    public static function get_mentor_requests_returns()
    {
        return
            new external_single_structure(
                [
                    'total' => new external_value(PARAM_INT, 'Total mentors requests', VALUE_DEFAULT, 0),
                    'requests' => new external_multiple_structure(
                        new external_single_structure(
                            [
                                'id' => new external_value(PARAM_INT, 'Mentor id'),
                                'firstname' => new external_value(PARAM_TEXT, 'Mentor firstname'),
                                'lastname' => new external_value(PARAM_TEXT, 'Mentor lastname'),
                                'profileimageurl' => new external_value(PARAM_URL, 'Mentor profile image url')
                            ]
                        ),
                        VALUE_DEFAULT,
                        []
                    )
                ]
            );
    }
}
