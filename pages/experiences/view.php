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
 * Experience view page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/dta/classes/cases.php');
require_once($CFG->dirroot . '/local/dta/classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/experiences.php');
require_once($CFG->dirroot . '/local/dta/classes/resources.php');
require_once($CFG->dirroot . '/local/dta/classes/sections.php');
require_once($CFG->dirroot . '/local/dta/classes/tinyeditorhandler.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/filterutils.php');
require_once($CFG->dirroot . '/local/dta/locallib.php');
require_once($CFG->dirroot . '/local/dta/classes/googlemeet/client.php');

use local_dta\Cases;
use local_dta\client;
use local_dta\Components;
use local_dta\Experiences;
use local_dta\helper;
use local_dta\Resources;
use local_dta\Sections;
use local_dta\TinyEditorHandler;
use local_dta\utils\FilterUtils;

require_login();

global $CFG, $PAGE, $OUTPUT, $USER;

function get_googlemeet_call_button()
{
    $client = new client(1);
    if (!$client->enabled) {
        return;
    }
    if ($client->check_login()) {
        $client->logout();
    }
    $meetingrecord = helper::get_googlemeet_record(1);
    if ($meetingrecord) {
        return '<button class="btn btn-primary btn-sm mt-2" onclick="window.open(\'https://meet.google.com/' . $meetingrecord->meetingcode . '\', \'_blank\');">' . get_string('tutoring:joinvideocall', 'local_dta') . '</button>';
    } else {
        return $client->print_login_popup(1);
    }
}

// Seting the page url and context
$id = required_param('id', PARAM_INT);
$PAGE->set_url(new moodle_url('/local/dta/pages/experiences/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->requires->js_call_amd('local_dta/reactions/manager', 'init');
$PAGE->requires->js_call_amd('local_dta/experiences/main', 'init');
$PAGE->requires->js_call_amd('local_dta/tutoring/google-meet', 'init');

// Get the experience
if (!$experience = Experiences::get_experience($id)) {
    throw new moodle_exception('invalidexperience', 'local_dta');
}

// Get the user and the user picture
$user = get_complete_user_data('id', $experience->userid);
$user_picture = new user_picture($user);
$user_picture->size = 101;
$experience_case = Cases::get_cases_by_experience($id);
$experience_case_info = [];
foreach ($experience_case as $case) {
    $case->description = ""; // SECTIONS TODO
    array_push($experience_case_info, $case);
}

// Get sections
$formated_sections = array();
$sections = Sections::get_sections([
    'component' => [Components::get_component_by_name('experience')->name],
    'contextid' => [$experience->id]
]);
foreach ($sections as $section) {
    $sectiongroupname = Sections::get_group($section->groupid)->name;
    array_push($formated_sections, [
        'header' => local_dta_get_element_translation('section_group', $sectiongroupname),
        'content' => $section->content ?? ''
    ]);
}

echo $OUTPUT->header();

(new TinyEditorHandler)->get_config_editor(['maxfiles' => 1]);

$template_context = [
    'component' => 'experience',
    'cases' => [
        'data' => $experience_case_info,
        'viewurl' => $CFG->wwwroot . '/local/dta/pages/cases/view.php?id='
    ],
    'experience' => [
        'data' => $experience,
        'pictureurl' => Experiences::get_picture_url($experience),
        'deleteurl' => $CFG->wwwroot . '/local/dta/pages/experiences/delete.php?id=',
        'editurl' => $CFG->wwwroot . '/local/dta/pages/experiences/manage.php?id=',
    ],
    'user' => [
        'id' => $user->id,
        'name' => $user->firstname . " " . $user->lastname,
        'email' => $user->email,
        'imageurl' => $user_picture->get_url($PAGE)->__toString(),
        'profileurl' => $CFG->wwwroot . '/local/dta/pages/profile/index.php?id=' . $user->id,
    ],
    'isview' => true,
    'isadmin' => is_siteadmin($USER),
    'showcontrols' => $experience->userid == $USER->id,
    'iconsurl' => $CFG->wwwroot . '/local/dta/icons/',
    'createcaseurl' => $CFG->wwwroot . '/local/dta/pages/cases/manage.php?id=',
    'reflection' => [], // SECTIONS TODO
    'reflectionsections' => $formated_sections,
    'mentorrepourl' => $CFG->wwwroot . '/local/dta/pages/mentors/index.php?id=' . $experience->id,
    //'related' => [
    //    'resources' => Resources::get_resources_by_context_component('experience', $id),
    //    'cases' => Cases::get_cases_by_context_component('experience', $id)
    //],
];


$template_context = FilterUtils::apply_filter_to_template_object($template_context);
$template_context['googlemeetcall']['button'] = get_googlemeet_call_button();
$meeting_record = helper::get_googlemeet_record(1);
$template_context['googlemeetcall']['closecall']  = $meeting_record ? $meeting_record->chatid : null;

echo $OUTPUT->render_from_template('local_dta/experiences/view/view', $template_context);

echo $OUTPUT->footer();
