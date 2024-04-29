<?php

/**
 * community page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_dta\Experience;
use local_dta\OurCases;
use local_dta\Reflection;
use local_dta\CONSTANTS;
use local_dta\utils\filter_utils;

require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/experience.php');
require_once(__DIR__ . './../../classes/ourcases.php');
require_once(__DIR__ . './../../classes/reflection.php');
require_once(__DIR__ . './../../classes/utils/filter_utils.php');


require_login();

global $CFG, $PAGE, $OUTPUT , $USER;

// Seting the page url and context
$id = required_param('id', PARAM_INT);
$PAGE->set_url(new moodle_url('/local/dta/pages/experiences/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->requires->js_call_amd('local_dta/reactions/manager', 'init');
$PAGE->requires->js_call_amd('local_dta/myexperience/view/main', 'init');

// Get the experience
if(!$experience = Experience::get_experience($id)) {
    throw new moodle_exception('invalidexperience', 'local_dta');
}

// Get the user and the user picture
$user = get_complete_user_data("id", $experience->userid);
$user_picture = new user_picture($user);
$user_picture->size = 101;
$experience_case = OurCases::get_cases_by_experience($id);
$experience_case_info = [];
foreach ($experience_case as $case) {
    $case = OurCases::get_section_header($case->id);
    $case->id = $case->ourcaseid;
    array_push($experience_case_info, $case);
}

// Get reflection if exist
$reflection = Reflection::check_exist_reflection_experience($id);
$formattedReflectionSections = array();

if ($reflection !== null && $reflection !== false) {
    $reflection->sections = Reflection::get_sections_by_groups($reflection->id, "ALL");

    foreach ($reflection->sections as $key => $section) {

        switch ($key) {
            case 'WHAT_INTRO':
                $key = get_string("experience_reflection_section_what_question_1_title", "local_dta");
                break;
            case 'WHAT_CONTEXT':
                $key = get_string("experience_reflection_section_what_question_2_title", "local_dta");
                break;
            case 'SO_WHAT_HOW':
                $key = get_string("experience_reflection_section_sowhat_question_1_title", "local_dta");
                break;
            case 'NOW_WHAT_ACTION':
                $key = get_string("experience_reflection_section_nowwhat_question_1_title", "local_dta");
                break;
            case 'NOW_WHAT_REFLECTION':
                $key = get_string("experience_reflection_section_nowwhat_question_2_title", "local_dta");
                break;
            case 'EXTRA':
                $key = get_string("experience_reflection_section_extra_question_1_title", "local_dta");
                break;
        }

        array_push($formattedReflectionSections, [
            'header' => $key,
            'content' => $section[0]->content ?? '',
        ]);
    }

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
        'pictureurl' => Experience::get_picture_url($experience),
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
    'reflection' => $reflection,
    'reflectionsections' => $formattedReflectionSections,
];

$template_context = filter_utils::apply_filter_to_template_object($template_context);

echo $OUTPUT->render_from_template('local_dta/experiences/view/view', $template_context);

echo $OUTPUT->footer();
