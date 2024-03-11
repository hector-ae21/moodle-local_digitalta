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

require_login();

use local_dta\Experience;

global $CFG, $PAGE, $OUTPUT , $USER;

$strings = get_strings(['myexperience_header' , 'myexperience_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/myexperience/dashboard.php'));
$PAGE->set_context(context_system::instance()) ;
$PAGE->set_title($strings->myexperience_title);
$PAGE->requires->js_call_amd('local_dta/reactions', 'init');

echo $OUTPUT->header();

$picture = new user_picture($USER);
$picture->size = 101;

$templatecontext = [
    "experiences" => [
        "data" => Experience::get_my_experiences($USER->id),
        "showimageprofile" => false,
        "showcontrols" => true,
        "showcontrolsadmin" => is_siteadmin($USER),
        "addurl" => $CFG->wwwroot . "/local/dta/pages/myexperience/manage.php",
        "viewurl" => $CFG->wwwroot . "/local/dta/pages/myexperience/view.php?id=",
    ], 
    "user"=> [
        "id" => $USER->id,
        "name" => $USER->firstname . " " . $USER->lastname,
        "email" => $USER->email,
        "imageurl" => $picture->get_url($PAGE)->__toString(),
        "editurl" => $CFG->wwwroot . "/user/editadvanced.php?id=" . $USER->id,
    ],
];


echo $OUTPUT->render_from_template('local_dta/myexperience/dashboard', $templatecontext);

echo $OUTPUT->footer();
