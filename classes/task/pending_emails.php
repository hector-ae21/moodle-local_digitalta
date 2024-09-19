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
 * Process pending emails
 *
 * @package    local_digitalta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace local_digitalta\task;

require_once($CFG->dirroot . '/config.php');

class pending_emails extends \core\task\adhoc_task
{

  public static function instance(
    int $tutorid,
    int $userid,
    string $subject,
    string $messagehtml
  ): self {
    $task = new self();
    $task->set_custom_data((object) [
      'subject' => $subject,
      'messagehtml' => $messagehtml,
      'tutorid' => $tutorid,
      'userid' => $userid
    ]);

    return $task;
  }

  /**
   * Execute the task.
   */
  public function execute()
  {
    global $DB;
    $data = $this->get_custom_data();
    $user = $DB->get_record('user', ['id' => $data->userid], '*', MUST_EXIST);
    $tutor = $DB->get_record('user', ['id' => $data->tutorid], '*', MUST_EXIST);
    email_to_user($tutor, $user, $data->subject, strip_tags($data->messagehtml), $data->messagehtml);
  }
}
