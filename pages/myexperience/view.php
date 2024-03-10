<?php

/**
 * community page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_dta\Experience;

require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/experiences.php');
require_once(__DIR__ . './../../classes/ourcases.php');


require_login();

global $CFG, $PAGE, $OUTPUT , $USER;


// Get the experience
$id = required_param('id', PARAM_INT);
if(!$experience = Experience::getExperience($id)) {
    throw new moodle_exception('invalidexperience', 'local_dta');
}

// Get the user and the user picture
$user = get_complete_user_data("id", $experience->user);
$user_picture = new user_picture($user);
$user_picture->size = 101;


// Seting the page url and context
$PAGE->set_url(new moodle_url('/local/dta/experience/view.php'));
$PAGE->set_context(context_system::instance());
$PAGE->requires->js_call_amd('local_dta/reactions', 'init');

echo $OUTPUT->header();

$templateContext = [
    'experience' => [
        'id' => $experience->id,
        'title' => $experience->title,
        'description' => $experience->description,
        'date' => $experience->date,
        'lang' => $experience->lang,
        'visible' => $experience->visible,
        'comments' => []
    ],
    'user' => [
        'id' => $user->id,
        'name' => $user->firstname . " " . $user->lastname,
        'email' => $user->email,
        'picture_url' => $user_picture->get_url($PAGE)->__toString(),
    ],
    'is_admin' => is_siteadmin($USER),
    'userprofile_url' => $CFG->wwwroot . "/user/profile.php?id=" . $user->id,
    'delete_url' => $CFG->wwwroot . "/local/dta/pages/experience/delete.php?id=",
    'edit_url' => $CFG->wwwroot . "/local/dta/pages/experience/edit.php?id=",
];

echo $OUTPUT->render_from_template('local_dta/experience/experience', $templateContext);

echo $OUTPUT->footer();
