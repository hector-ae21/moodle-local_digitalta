<?php

/**
 * community page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/experiences.php');
require_once(__DIR__ . './../../classes/cases.php');
require_once(__DIR__ . './../../classes/resources.php');
require_once(__DIR__ . './../../classes/sections.php');
require_once(__DIR__ . './../../classes/constants.php');
require_once(__DIR__ . './../../classes/utils/filter_utils.php');
require_once($CFG->dirroot . '/local/dta/locallib.php');

use local_dta\Experiences;
use local_dta\Cases;
use local_dta\Resources;
use local_dta\Sections;
use local_dta\CONSTANTS;
use local_dta\utils\filter_utils;

require_login();

global $CFG, $PAGE, $OUTPUT , $USER;

// Seting the page url and context
$id = required_param('id', PARAM_INT);
$PAGE->set_url(new moodle_url('/local/dta/pages/experiences/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->requires->js_call_amd('local_dta/reactions/manager', 'init');
$PAGE->requires->js_call_amd('local_dta/myexperience/view/main', 'init');

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
    'componentname' => ['experience'],
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
    "instance" => CONSTANTS::REACTIONS_INSTANCES['EXPERIENCE'],
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

$template_context = filter_utils::apply_filter_to_template_object($template_context);

echo $OUTPUT->render_from_template('local_dta/experiences/view/view', $template_context);

echo $OUTPUT->footer();
