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
 * myexperience manage page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 require_once(__DIR__ . '/../../../../config.php');
 require_once(__DIR__ . './../../classes/experience.php');
 require_once(__DIR__ . './../../classes/form/experiences_form.php');

$id = optional_param('id', 0, PARAM_INT);
$title = $_POST['experiencetitle'] ?? '';

require_login();
use local_dta\Experience;

global $CFG, $PAGE, $OUTPUT, $USER, $DB;
$strings = get_strings(['form_experience_header'], "local_dta");

$context = context_system::instance();
$PAGE->set_url('/local/dta/pages/myexperience/manage.php', ['id' => $id]);
$PAGE->set_context($context);
$PAGE->set_title($strings->form_experience_header);
$PAGE->set_heading($strings->form_experience_header);

$form = new local_experiences_form();

if ($form->is_cancelled()) {
    redirect(new moodle_url('/local/dta/pages/myexperience/manage.php', ['id' => $id]));
} elseif ($data = $form->get_data()) {

    // Add the experience
    $data->userid = $USER->id;
    $data->date = date("Y-m-d H:i:s");
    $experience = Experience::store($data);

    // Process the picture
    file_save_draft_area_files(
        $data->picture,
        $context->id,
        'local_dta',
        'picture',
        $experience->__get('id'),
        [
            'subdirs' => false,
            'maxfiles' => 1
        ]
    );

    redirect($experience->get_url());
} else {
    if ($id) {
        // Get the experience data
        $experience = Experience::get_experience($id);

        local_dta_check_permissions($experience, $USER);

        // Get the current picture draft id
        $experience->picture = file_get_submitted_draft_itemid('picture');
        file_prepare_draft_area(
            $experience->picture,
            $context->id,
            'local_dta',
            'picture',
            $id,
            [
                'subdirs' => false,
                'maxfiles' => 1
            ]
        );

        // Set the description as a text format
        $experience->description = ['text' => $experience->description];

        // Set the form data
        $form->set_data($experience);
    } elseif ($title != "") {
        $data = new stdClass();
        $data->title = $title;
        $form->set_data($data);
    }
    echo $OUTPUT->header();
    $form->display();
    echo $OUTPUT->footer();
}
