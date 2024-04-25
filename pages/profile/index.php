<?php

/**
 * profile 
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/experience.php');
require_once(__DIR__ . './../../classes/ourcases.php');
require_once(__DIR__ . './../../classes/utils/string_utils.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/filter_utils.php');

$id = required_param('id', PARAM_INT);

require_login();

use local_dta\Experience;
use local_dta\OurCases;
use local_dta\utils\StringUtils;
use local_dta\utils\filter_utils;

global $CFG, $PAGE, $OUTPUT , $USER;

$strings = get_strings(['profile_header' , 'profile_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/profile/index.php', ['id' => $id]));
$PAGE->set_context(context_system::instance()) ;
$PAGE->set_title($strings->profile_title);
$PAGE->requires->js_call_amd('local_dta/myexperience/reactions', 'init');

echo $OUTPUT->header();

// get user by id
$user = get_complete_user_data("id", $id);

$picture = new user_picture($user);
$picture->size = 101;
$experiences = Experience::get_experiences_by_user($user->id);
$experiences = array_map(function ($experience) {
    $experience->description = StringUtils::truncateHtmlText($experience->description);
    $experience->reactions = false;
    return $experience;
}, $experiences);
$cases = OurCases::get_cases_by_user($user->id);
$full_cases = array();

if (!empty($cases)) {
    $full_cases = array_values(array_map(function ($case) {
        $object = OurCases::get_section_header($case->id);
        $object->description = StringUtils::truncateHtmlText($object->description, 500);
        $object->timecreated = $case->timecreated;
        $object->user = $case->user;
        $object->pictureurl = $case->pictureurl;
        $object->reactions = false;
        return $object;
    }, $cases));
}

$templatecontext = [
    "experiences" => [
        "data" => $experiences,
        "showimageprofile" => false,
        "showcontrols" => true,
        "showcontrolsadmin" => is_siteadmin($USER),
        "showaddbutton" => $user->id == $USER->id,
        "addurl" => $CFG->wwwroot . "/local/dta/pages/experiences/manage.php",
        "viewurl" => $CFG->wwwroot . "/local/dta/pages/experiences/view.php?id=",
    ], 
    "user"=> [
        "id" => $user->id,
        "name" => $user->firstname . " " . $user->lastname,
        "email" => $user->email,
        "imageurl" => $picture->get_url($PAGE)->__toString(),
        "editurl" => $CFG->wwwroot . "/user/editadvanced.php?id=" . $user->id,
    ],
    "cases" => array_values($full_cases),
    "url_create_case" => $CFG->wwwroot . '/local/dta/pages/cases/manage.php',
    "url_case" => $CFG->wwwroot . '/local/dta/pages/cases/view.php?id='
];

$templatecontext = filter_utils::apply_filter_to_template_object($templatecontext);


echo $OUTPUT->render_from_template('local_dta/profile/profile', $templatecontext);

echo $OUTPUT->footer();
