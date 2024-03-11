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
require_once(__DIR__ . './../../classes/experience.php');
require_once(__DIR__ . './../../classes/ourcases.php');


require_login();

global $CFG, $PAGE, $OUTPUT , $USER;

// Seting the page url and context
$id = required_param('id', PARAM_INT);
$PAGE->set_url(new moodle_url('/local/dta/pages/myexperience/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->requires->js_call_amd('local_dta/reactions', 'init');

// Get the experience
if(!$experience = Experience::get_experience($id)) {
    throw new moodle_exception('invalidexperience', 'local_dta');
}

// Get the user and the user picture
$user = get_complete_user_data("id", $experience->userid);
$user_picture = new user_picture($user);
$user_picture->size = 101;


echo $OUTPUT->header();

$templateContext = [
    'experience' => [
        'id' => $experience->id,
        'title' => $experience->title,
        'description' => $experience->description,
        'date' => $experience->date,
        'lang' => $experience->lang,
        'visible' => $experience->visible,
        'pictureurl' => Experience::get_picture_url($experience),
        'deleteurl' => $CFG->wwwroot . "/local/dta/pages/myexperience/delete.php?id=",
        'editurl' => $CFG->wwwroot . "/local/dta/pages/myexperience/manage.php?id=",
        'comments' => []
    ],
    'user' => [
        'id' => $user->id,
        'name' => $user->firstname . " " . $user->lastname,
        'email' => $user->email,
        'imageurl' => $user_picture->get_url($PAGE)->__toString(),
        'profileurl' => $CFG->wwwroot . '/user/profile.php?id=' . $user->id,
    ],
    'isadmin' => is_siteadmin($USER),
    'createcaseurl' => $CFG->wwwroot . "/local/dta/pages/ourcases/manage.php?id=",
];

echo $OUTPUT->render_from_template('local_dta/myexperience/view', $templateContext);

echo $OUTPUT->footer();
