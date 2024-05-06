<?php

/**
 * profile 
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/mentor.php');

require_login();

use local_dta\Mentor;

global $CFG, $PAGE, $OUTPUT , $USER;

// $strings = get_strings(['profile_header' , 'profile_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/mentors/index.php'));
$PAGE->set_context(context_system::instance()) ;
$PAGE->set_title("Mentors");
// $PAGE->requires->js_call_amd('local_dta/myexperience/reactions', 'init');

echo $OUTPUT->header();

// get all mentors (now all users)
$mentors = array_values(Mentor::get_all_mentors());

$formattedMentors = (object)[];

foreach ($mentors as $mentor) {
    $formattedMentors->id = $mentor->id;
    $formattedMentors->name = $mentor->firstname . " " . $mentor->lastname;
    $formattedMentors->position = "Teacher at University of Salamanca";
    $formattedMentors->imageurl = $CFG->wwwroot . "/local/dta/pages/profile/index.php?id=" . $mentor->id;
    $formattedMentors->viewprofileurl = $CFG->wwwroot . "/local/dta/pages/profile/index.php?id=" . $mentor->id;
    $formattedMentors->addcontacturl = "#";
    $formattedMentors->sendemailurl = "#";
    $formattedMentors->tags = [
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
    $formattedMentors->themes = [
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
}

$templatecontext = [
    "mentors"=> $formattedMentors,
    "ismentorcardvertical" => true,
];

print_object($templatecontext);

// $templatecontext = filter_utils::apply_filter_to_template_object($templatecontext);

echo $OUTPUT->render_from_template('local_dta/mentors/dashboard', $templatecontext);

echo $OUTPUT->footer();
