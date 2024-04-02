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

require_once(__DIR__ . '/../../../../../config.php');
require_once(__DIR__ . './../../classes/reflection.php');

use local_dta\Reflection;


require_login();

global $CFG, $PAGE, $OUTPUT , $USER;

// Seting the page url and context
$id = required_param('id', PARAM_INT);
$PAGE->set_url(new moodle_url('/local/dta/pages/experiences/reflection/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());

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
    array_push($experience_case_info, $case);
}

$reflection = new stdClass();

// Get reflection sections
$reflection->sections = Reflection::get_sections_by_groups($reflection->id,"ALL");


echo $OUTPUT->header();

$template_context = [
    'reflection' => $reflection,  
];

echo $OUTPUT->render_from_template('local_dta/experiences/reflection/view', $template_context);

echo $OUTPUT->footer();
