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
 * WebService chat services
 *
 * @package   local_digitalta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/digitalta/classes/chat.php');
require_once($CFG->dirroot . '/local/digitalta/classes/googlemeet/helper.php');
require_once($CFG->dirroot . '/local/digitalta/classes/googlemeet/client.php');


use local_digitalta\Chat;
use local_digitalta\GoogleMeetHelper;

/**
 * This class is used to delete a context
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_chats_get_messages extends external_api
{
    public static function chats_get_messages_parameters()
    {
        return new external_function_parameters(
            array(
                'chatid' => new external_value(PARAM_INT, 'ID'),
                'userid' => new external_value(PARAM_INT, 'User ID', VALUE_DEFAULT, null),
            )
        );
    }

    public static function chats_get_messages($chatid, $userid=null)
    {
        if (is_null($userid)) {
            global $USER;
            $userid = $USER->id;
        }
        $messages = Chat::get_chat_messages($chatid, $userid);
        $chatroom = Chat::get_chat_room($chatid);

        $chatroom->videocall = new stdClass();
        $chatroom->videocall->button = GoogleMeetHelper::get_googlemeet_call_button($chatid, true);
        $meeting_record = GoogleMeetHelper::get_googlemeet_record($chatid);
        $chatroom->videocall->closebutton  = $meeting_record ? $meeting_record->chatid : null;

        return [
            'result' => true,
            'messages' => array_values($messages),
            'chatroom' => $chatroom,
        ];
    }

    public static function chats_get_messages_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'error' => new external_value(PARAM_RAW, 'Error message', VALUE_OPTIONAL),
                'chatroom' => new external_single_structure(
                    [
                        'id' => new external_value(PARAM_INT, 'ID'),
                        'experienceid' => new external_value(PARAM_INT, 'Experience ID'),
                        'name' => new external_value(PARAM_TEXT, 'Name'),
                        'users' => new external_multiple_structure(
                            new external_single_structure(
                                [
                                    'userid' => new external_value(PARAM_INT, 'User ID'),
                                    'firstname' => new external_value(PARAM_TEXT, 'First Name'),
                                    'lastname' => new external_value(PARAM_TEXT, 'Last Name'),
                                ]
                            )
                        ),
                        'videocall' => new external_single_structure(
                            [
                                'button' => new external_value(PARAM_RAW, 'Button'),
                                'closebutton' => new external_value(PARAM_INT, 'Close Button', VALUE_OPTIONAL)
                            ]
                        ),
                        'timecreated' => new external_value(PARAM_INT, 'Time Created'),
                        'timemodified' => new external_value(PARAM_INT, 'Time Modified'),
                    ]
                ),
                'messages' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'id' => new external_value(PARAM_INT, 'ID'),
                            'chatid' => new external_value(PARAM_INT, 'Chat ID'),
                            'userid' => new external_value(PARAM_INT, 'User ID'),
                            'message' => new external_value(PARAM_TEXT, 'Message'),
                            'timecreated' => new external_value(PARAM_INT, 'Time Created'),
                            'timemodified' => new external_value(PARAM_INT, 'Time Modified'),
                            'is_mine' => new external_value(PARAM_BOOL, 'Is Mine'),
                            'userfullname' => new external_value(PARAM_TEXT, 'User Full Name'),
                            'userpicture' => new external_value(PARAM_TEXT, 'User Picture URL'),
                        ]
                    )
                ),
            ]
        );
    }
}
