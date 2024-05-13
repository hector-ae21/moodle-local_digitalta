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
 * WebService to delete a context
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/mentors.php');

use local_dta\Mentor;



/**
 * This class is used to delete a context
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_mentors_load extends external_api
{
    /**
     * Define the parameters for the function
     *
     * @return external_function_parameters
     */
    public static function load_mentors_parameters()
    {
        return new external_function_parameters(
            array(
                'numLoaded' => new external_value(PARAM_INT, 'Number of mentors already loaded'),
                'numToLoad' => new external_value(PARAM_INT, 'Number of mentors to load'),
            )
        );
    }

    /**
     * Define the parameters for the function
     *
     * @return external_function_parameters
     */
    public static function load_mentors(int $numLoaded, int $numToLoad)
    {
        global $CFG, $PAGE;
        $PAGE->set_context(context_system::instance());

        $mentors = Mentor::get_mentor_chunk($numLoaded, $numToLoad);
        $formattedMentors = [];

        foreach ($mentors as $mentor) {
            $newMentor = new stdClass();
            $newMentor->id = $mentor->id;
            $newMentor->name = $mentor->firstname . " " . $mentor->lastname;
            $newMentor->position = "Teacher at University of Salamanca";

            $mentor_picture = new user_picture($mentor);
            $mentor_picture->size = 101;
            $newMentor->imageurl = $mentor_picture->get_url($PAGE)->__toString();
            $newMentor->viewprofileurl = $CFG->wwwroot . "/local/dta/pages/profile/index.php?id=" . $mentor->id;
            $newMentor->addcontacturl = "#";
            $newMentor->sendemailurl = "#";
            $newMentor->tags = [
                [
                    "id" => 1,
                    "name" => "Tag 1",
                ],
                [
                    "id" => 2,
                    "name" => "Tag 2",
                ],
                [
                    "id" => 3,
                    "name" => "Tag 3",
                ],
            ];
            $newMentor->themes = [
                [
                    "id" => 1,
                    "name" => "Theme 1",
                ],
                [
                    "id" => 2,
                    "name" => "Theme 2",
                ],
                [
                    "id" => 3,
                    "name" => "Theme 3",
                ],
            ];

            array_push($formattedMentors, $newMentor);
        }

        return [
            'result' => true,
            'mentors' => $formattedMentors,
        ];
    }

    /*
     * Define the return value
     *
     * @return external_single_structure
     */
    public static function load_mentors_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'error' => new external_value(PARAM_RAW, 'Error message' , VALUE_OPTIONAL),
                'mentors' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'id' => new external_value(PARAM_INT, 'ID'),
                            'name' => new external_value(PARAM_TEXT, 'Name'),
                            'position' => new external_value(PARAM_TEXT, 'Position'),
                            'themes' => new external_multiple_structure(
                                new external_single_structure(
                                    [
                                        'id' => new external_value(PARAM_INT, 'ID'),
                                        'name' => new external_value(PARAM_TEXT, 'Name')
                                    ]
                                )
                            ),
                            'tags' => new external_multiple_structure(
                                new external_single_structure(
                                    [
                                        'id' => new external_value(PARAM_INT, 'ID'),
                                        'name' => new external_value(PARAM_TEXT, 'Name')
                                    ]
                                )
                            ),
                            'imageurl' => new external_value(PARAM_TEXT, 'Image URL'),
                            'viewprofileurl' => new external_value(PARAM_TEXT, 'View profile URL'),
                            'addcontacturl' => new external_value(PARAM_TEXT, 'Add contact URL'),
                            'sendemailurl' => new external_value(PARAM_TEXT, 'Send email URL'),
                        ]
                    )
                ),
            ]
        );
    }

    
}
