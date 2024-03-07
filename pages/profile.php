<?php

/**
 * profile 
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../config.php');
require_login();

require_once(__DIR__ . './../classes/experiences.php');
use local_dta\Experience;

global $CFG, $PAGE, $OUTPUT , $USER;

$strings = get_strings(['profile_header' , 'profile_title'], "local_dta");

// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/community.php'));
$PAGE->set_context(context_system::instance()) ;
$PAGE->set_title($strings->profile_title);
$PAGE->requires->js_call_amd('local_dta/reactions', 'init');

echo $OUTPUT->header();

$picture = new user_picture($USER);
$picture->size = 101;

$templatecontext = [
    "experiences" => [
        "data" => Experience::getMyExperiences($USER->id),
        "show_image_profile" => false,
        "show_controls" => true,
        "show_controls_admin" => is_siteadmin($USER),
    ], 
    "profile"=> [
        "id" => $USER->id,
        "name" => $USER->firstname . " " . $USER->lastname,
        "email" => $USER->email,
        "image" => $picture->get_url($PAGE)->__toString(),
    ],
    "edit_profile_url" => $CFG->wwwroot . "/user/editadvanced.php?id=" . $USER->id ,
    "add_url" => $CFG->wwwroot . "/local/dta/pages/experience/add.php",
];


echo $OUTPUT->render_from_template('local_dta/profile/profile', $templatecontext);

echo $OUTPUT->footer();
