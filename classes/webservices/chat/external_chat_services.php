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
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/chat/chat.php');

use local_dta\Chat;

/**
 * This class is used to delete a context
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_chat_services extends external_api
{
    // SERVICE : local_dta_get_chat_rooms
    public static function get_chat_rooms_parameters()
    {
        return new external_function_parameters(
            array(
            )
        );
    }

    public static function get_chat_rooms()
    {
        $rooms = Chat::get_chat_rooms();

        return [
            'result' => true,
            'chat_rooms' => $rooms
        ];
    }

    public static function get_chat_rooms_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'chat_rooms' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'id' => new external_value(PARAM_INT, 'ID'),
                            'chatid' => new external_value(PARAM_INT, 'Chat ID'),
                            'userid' => new external_value(PARAM_INT, 'User ID'),
                            'timecreated' => new external_value(PARAM_TEXT, 'Time Created'),
                            'timemodified' => new external_value(PARAM_TEXT, 'Time Modified'),
                            'experienceid' => new external_value(PARAM_INT, 'Experience ID')
                        ]
                    )
                ),
                'error' => new external_value(PARAM_RAW, 'Error message', VALUE_OPTIONAL)
            ]
        );
    }


    // SERVICE : local_dta_add_user_to_chat_room
    public static function add_user_to_chat_room_parameters()
    {
        return new external_function_parameters(
            array(
                'chatid' => new external_value(PARAM_INT, 'ID'),
                'userid' => new external_value(PARAM_INT, 'User ID')
            )
        );
    }

    public static function add_user_to_chat_room($chatid, $userid)
    {
        $is = Chat::add_user_to_chat_room($chatid, $userid);

        return [
            'result' => $is,
        ];
    }

    public static function add_user_to_chat_room_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'error' => new external_value(PARAM_RAW, 'Error message', VALUE_OPTIONAL)
            ]
        );
    }


    // SERVICE : local_dta_add_message
    public static function add_message_parameters()
    {
        return new external_function_parameters(
            array(
                'chatid' => new external_value(PARAM_INT, 'ID'),
                'userid' => new external_value(PARAM_INT, 'User ID'),
                'message' => new external_value(PARAM_TEXT, 'Message')
            )
        );
    }

    public static function add_message($chatid, $userid, $message)
    {
        $is = Chat::add_message_to_chat_room($chatid, $userid, $message);
        
        return [
            'result' => $is,
        ];
    }

    public static function add_message_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'error' => new external_value(PARAM_RAW, 'Error message', VALUE_OPTIONAL)
            ]
        );
    }



    
}
