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
 * Experience delete page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/dta/lib.php');
require_once($CFG->dirroot . '/local/dta/classes/experiences.php');

use local_dta\Experiences;

require_login();

global $CFG, $PAGE, $OUTPUT, $USER;

$strings = get_strings(['form_experience_delete_header', 'form_experience_delete_confirm', 'form_experience_delete_yes', 'form_experience_delete_no'], "local_dta");

// Get the experience id
$id = required_param('id', PARAM_INT);
$delete = optional_param('delete', '', PARAM_ALPHANUM);

$PAGE->set_url(new moodle_url('/local/dta/pages/experiences/delete.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->form_experience_delete_header);

$experience = Experiences::get_experience($id);


// Check permissions
// if (local_dta_check_permissions($experience, $USER) == false) {
//     print_error('errorpermissions', 'local_dta');
// }

// Check if the delete hash is correct
if ($delete === md5($experience->timecreated)) {
    if (!Experiences::delete_experience($experience)) {
        print_error('errordeleteexperience', 'local_dta');
    }
    redirect(new moodle_url('/local/dta/pages/experiences/dashboard.php'), get_string('form_experience_delete_yes', 'local_dta'));
    exit;
}

$continueurl = new moodle_url('/local/dta/pages/experiences/delete.php', array('id' => $experience->id, 'delete' => md5($experience->timecreated)));
$backurl = new moodle_url('/local/dta/pages/experiences/view.php', ['id' => $experience->id]);
$continuebutton = new single_button(
    $continueurl,
    get_string('delete'),
    'post',
    false,
    ['data-action' => 'delete']
);

echo $OUTPUT->header();
echo $OUTPUT->confirm("{$strings->form_experience_delete_confirm} <br><br> {$experience->title}", $continuebutton, $backurl);
echo $OUTPUT->footer();
