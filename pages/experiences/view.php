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
require_once($CFG->dirroot . '/local/dta/classes/utils/filterutils.php');
require_once($CFG->dirroot . '/local/dta/locallib.php');

use local_dta\Cases;
use local_dta\Components;
use local_dta\Experiences;
use local_dta\Resources;
use local_dta\Sections;
use local_dta\utils\FilterUtils;

require_login();

global $CFG, $PAGE, $OUTPUT , $USER;

// Seting the page url and context
$id = required_param('id', PARAM_INT);
$PAGE->set_url(new moodle_url('/local/dta/pages/experiences/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->requires->js_call_amd('local_dta/reactions/manager', 'init');
$PAGE->requires->js_call_amd('local_dta/experiences/view/main', 'init');

// Get the experience
if(!$experience = Experiences::get_experience($id)) {
    throw new moodle_exception('invalidexperience', 'local_dta');
}

// Get the user and the user picture
$user = get_complete_user_data("id", $experience->userid);
$user_picture = new user_picture($user);
$user_picture->size = 101;
$experience_case = Cases::get_cases_by_experience($id);
$experience_case_info = [];
foreach ($experience_case as $case) {
    $case->description = ""; // TODO SECTIONS
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

$template_context = [
    "instance" => Components::get_component_by_name('experience')->id,
    'cases' => [
        'data' => $experience_case_info,
        'viewurl' => $CFG->wwwroot . '/local/dta/pages/cases/view.php?id='
    ],
    'experience' => [
        'data' => $experience,
        'pictureurl' => Experiences::get_picture_url($experience),
        'deleteurl' => $CFG->wwwroot . "/local/dta/pages/experiences/delete.php?id=",
        'editurl' => $CFG->wwwroot . "/local/dta/pages/experiences/manage.php?id=",
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
    'createcaseurl' => $CFG->wwwroot . "/local/dta/pages/cases/manage.php?id=",
    'createreflectionurl' => $CFG->wwwroot . '/local/dta/pages/experiences/reflection.php?id=',
    'viewreflectionurl' => $CFG->wwwroot . '/local/dta/pages/experiences/reflection/view.php?id=',
    'reflection' => [], // TODO SECTIONS
    'reflectionsections' => $formated_sections,
    //'related' => [
    //    'resources' => Resources::get_resources_by_context_component('experience', $id),
    //    'cases' => Cases::get_cases_by_context_component('experience', $id)
    //],
];

$template_context = FilterUtils::apply_filter_to_template_object($template_context);

echo $OUTPUT->render_from_template('local_dta/experiences/view/view', $template_context);

echo $OUTPUT->footer();
