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

define('AJAX_SCRIPT', true);

require_once(__DIR__ . './../../../../config.php');
require_once($CFG->dirroot . '/local/dta/classes/chat/chat.php');

use local_dta\Chat;


$action       = optional_param('action', '', PARAM_ALPHANUM);

if (!isloggedin()) {
    throw new moodle_exception('notlogged', 'chat');
}

switch ($action) {
    case 'get_chat_rooms':
        $chat_rooms = Chat::get_chat_rooms();
        echo json_encode($chat_rooms);
        break;
    default:
        echo json_encode(array('error' => 'Invalid action'));
        break;
}