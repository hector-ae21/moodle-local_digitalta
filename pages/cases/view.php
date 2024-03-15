<?php

/**
 * ourcases view page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/ourcases.php');

use local_dta\OurCases;

require_login();

global $CFG, $PAGE, $OUTPUT, $USER;

$id = optional_param('id', 0, PARAM_INT);

$strings = get_strings(['ourcases_header', 'ourcases_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/cases/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->ourcases_title);


echo $OUTPUT->header();

if (!$ourcase = OurCases::get_case($id)) {
    throw new moodle_exception('invalidcases', 'local_dta');
}

$sections = array_values(OurCases::get_sections_text($ourcase->id));

if (!$section_header = OurCases::get_section_header($ourcase->id)) {
    throw new moodle_exception('invalidcasessection', 'local_dta');
}

$user = get_complete_user_data("id", $ourcase->userid);
$picture = new user_picture($user);
$picture->size = 101;
$user->imageurl = $picture->get_url($PAGE)->__toString();

$templateContext = [
    'sections' => $sections,
    'sectionheader' => $section_header,
    'ourcase' => $ourcase,
    'editurl' => new moodle_url('/local/dta/pages/cases/manage.php', ['caseid' => $ourcase->id]),
    'deleteurl' => new moodle_url('/local/dta/pages/cases/delete.php', ['id' => $ourcase->id]),
    'user' => $user,
];


echo $OUTPUT->render_from_template('local_dta/cases/view/view', $templateContext);


echo $OUTPUT->footer();
