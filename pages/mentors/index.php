<?php

/**
 * profile 
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/mentors.php');

require_login();

use local_dta\Mentor;

global $CFG, $PAGE, $OUTPUT , $USER;

$strings = get_strings(['mentor_page_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/mentors/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->mentor_page_title);
$PAGE->requires->js_call_amd('local_dta/mentors/main', 'init');

echo $OUTPUT->header();

// get chunk of mentors
$numToLoad = 10; // number of mentors to load
$mentors = Mentor::get_mentor_chunk(0, $numToLoad);
$formattedMentors = [];



foreach ($mentors as $mentor) {
    $newMentor = new stdClass();
    $newMentor->id = $mentor->id;
    $newMentor->name = $mentor->firstname . " " . $mentor->lastname;
    $newMentor->position = "Teacher at University of Salamanca";

    $mentor_picture = new user_picture($mentor);
    $mentor_picture->size = 101;
    $newMentor->imageurl = $mentor_picture->get_url($PAGE)->__toString();

    $newMentor->viewprofileurl = $CFG->wwwroot . "/local/dta/pages/profile/index.php?id=" . $mentor->id;
    $newMentor->addcontacturl = "#";
    $newMentor->sendemailurl = "#";
    $newMentor->tags = [
        [
            "id" => 1,
            "name" => "Tag 1",
        ],
        [
            "id" => 2,
            "name" => "Tag 2",
        ],
        [
            "id" => 3,
            "name" => "Tag 3",
        ],
    ];
    $newMentor->themes = [
        [
            "id" => 1,
            "name" => "Theme 1",
        ],
        [
            "id" => 2,
            "name" => "Theme 2",
        ],
        [
            "id" => 3,
            "name" => "Theme 3",
        ],
    ];

    array_push($formattedMentors, $newMentor);
}

$templatecontext = [
    "mentors"=> $formattedMentors,
    "ismentorcardvertical" => true,
    "numToLoad" => $numToLoad,
];

// $templatecontext = filter_utils::apply_filter_to_template_object($templatecontext);

echo $OUTPUT->render_from_template('local_dta/mentors/dashboard', $templatecontext);

echo $OUTPUT->footer();
