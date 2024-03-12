<?php

/**
 * ourcases manage page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/experience.php');
require_once(__DIR__ . './../../classes/ourcases.php');

use local_dta\Experience;
use local_dta\OurCases;

require_login();

global $CFG, $PAGE, $OUTPUT , $USER;

$id = required_param('id', PARAM_INT);

$strings = get_strings(['experiencesheader', 'experiencestitle'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/ourcases/manage.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->experiencestitle);


if(!$experience = Experience::get_experience($id)) {
    throw new moodle_exception('invalidaourcases', 'local_dta');
}

if(!$ourcase = OurCases::get_case_by_experience($id)) {
    $ourcase = OurCases::add_case($id , date("Y-m-d H:i:s") , $USER->id); 
}

$sections = OurCases::get_sections_text($ourcase->id);

if(!$section_header = OurCases::get_section_header($ourcase->id)) {
    throw new moodle_exception('invalidourcasessection', 'local_dta');
}




echo $OUTPUT->header();

$templateContext = [
    'experience' => $experience,
    'sectionheader' => $section_header ,
    'sections' => $sections,
    'ourcase' => $ourcase
];

$PAGE->requires->js_call_amd('local_dta/ourcases', 'init');

echo $OUTPUT->render_from_template('local_dta/ourcases/manage', $templateContext);

echo $OUTPUT->footer();
