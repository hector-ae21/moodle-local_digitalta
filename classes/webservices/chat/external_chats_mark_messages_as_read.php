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
 * WebService Mark chat message(s) as read
 *
 * @package   local_digitalta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/digitalta/classes/chat.php');

use local_digitalta\Chat;

class external_chats_mark_messages_as_read extends external_api
{
  public static function chats_mark_messages_as_read_parameters()
  {
    return new external_function_parameters(
      array(
        'chatid' => new external_value(PARAM_INT, 'Chat ID'),
        'messageids' => new external_multiple_structure(
          new external_value(PARAM_INT, 'Message ID'),
          'Message IDs',
          VALUE_DEFAULT,
          array()
        ),
        'userid' => new external_value(PARAM_INT, 'User ID', VALUE_DEFAULT, null)

      )
    );
  }

  public static function chats_mark_messages_as_read($chatid, $messageids = array(), $userid = null)
  {
    global $USER;
    $userid = is_null($userid) ? $USER->id : $userid;
    $result = Chat::mark_messages_as_read($chatid, $userid, $messageids);
    return [
      'success' => $result
    ];
  }

  public static function chats_mark_messages_as_read_returns()
  {
    return new external_single_structure(
      array(
        'success' => new external_value(PARAM_BOOL, 'Result')
      )
    );
  }
}