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

require_login();

use local_dta\Experience;

global $CFG, $PAGE, $OUTPUT;

$strings = get_strings(['community_header', 'community_title'], "local_dta");

// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/pages/community/dashboard.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->community_title);

echo $OUTPUT->header();

$experiences = Experience::getAllExperiences(false);

$user = get_complete_user_data("id", $USER->id);
$picture = new user_picture($user);
$picture->size = 101;
$user->imageurl = $picture->get_url($PAGE)->__toString();

$templateContext = [
    "user" => $user,
    "experiences" => [
        "data" => $experiences,
        "showimageprofile" => true,
        "showcontrols" => false,
        "showcontrolsadmin" => is_siteadmin($USER),
        "addurl" => $CFG->wwwroot . "/local/dta/pages/myexperience/add.php",
        "viewurl" => $CFG->wwwroot . '/local/dta/pages/myexperience/view.php?id='
    ]
];

echo $OUTPUT->render_from_template('local_dta/community/dashboard', $templateContext);

echo $OUTPUT->footer();
