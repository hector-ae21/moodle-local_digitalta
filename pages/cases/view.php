<?php

/**
 * ourcases view page
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

global $CFG, $PAGE, $OUTPUT, $USER;

$id = optional_param('id', 0, PARAM_INT);

$strings = get_strings(['experiences_header', 'experiences_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/cases/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->experiences_title);
$PAGE->requires->js_call_amd('local_dta/ourcases/manage', 'init');

echo $OUTPUT->header();

if (!$ourcase = OurCases::get_case_by_experience($id)) {
    throw new moodle_exception('invalidcases', 'local_dta');
}

$sections = array_values(OurCases::get_sections_text($ourcase->id , true) );


$templateContext = [
    'sections' => $sections,
    'ourcase' => $ourcase,
];

echo $OUTPUT->render_from_template('local_dta/cases/manage-with-experience', $templateContext);


echo $OUTPUT->footer();
