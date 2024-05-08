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
 * WebService to get comments
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/reactions.php');

use local_dta\Components;
use local_dta\Reactions;

/**
 * This class is used to get comments
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_reactions_get_comments extends external_api
{
    /**
     * Returns the description of the external function parameters
     *
     * @return external_function_parameters The external function parameters
     */
    public static function reactions_get_comments_parameters()
    {
        return new external_function_parameters(
            [
                'component' => new external_value(PARAM_TEXT, 'Component'),
                'componentinstance' => new external_value(PARAM_INT, 'Component instance')
            ]
        );
    }

    /**
     * Gets comments
     *
     * @param  string $component Component
     * @param  int    $componentinstance Component instance
     * @return array  Array with the result and the comments
     */
    public static function reactions_get_comments($component, $componentinstance)
    {
        global $DB;

        $component = Components::get_component_by_name($component);
        if (!$comments = Reactions::get_comments_for_component($component->id, $componentinstance)) {
            return ['result' => false, 'error' => 'Error getting comments'];
        }

        foreach ($comments as $comment) {
            $user = $DB->get_record('user', ['id' => $comment->userid]);
            $comment->user            = new \stdClass();
            $comment->user->id        = $user->id;
            $comment->user->username  = $user->username;
            $comment->user->firstname = $user->firstname;
            $comment->user->lastname  = $user->lastname;
            $comment->user->fullname  = fullname($user);
            $comment->user->email     = $user->email;
        }

        return ['result' => true, 'comments' => $comments];
    }


    /**
     * Returns the description of the external function return value
     *
     * @return external_single_structure The external function return value
     */
    public static function reactions_get_comments_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'comments' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'id' => new external_value(PARAM_INT, 'ID'),
                            'user' => new external_single_structure(
                                [
                                    'id' => new external_value(PARAM_INT, 'ID'),
                                    'username' => new external_value(PARAM_RAW, 'Username'),
                                    'firstname' => new external_value(PARAM_RAW, 'First Name'),
                                    'lastname' => new external_value(PARAM_RAW, 'Last Name'),
                                    'fullname' => new external_value(PARAM_RAW, 'Full Name'),
                                    'email' => new external_value(PARAM_RAW, 'Email'),
                                ]
                            ),
                            'comment' => new external_value(PARAM_RAW, 'Comment'),
                        ]
                    )
                )
            ]
        );
    }
}
