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
require_once(__DIR__ . './../../classes/ourcases.php');

use local_dta\Experience;
use local_dta\OurCases;

require_login();

global $CFG, $PAGE, $OUTPUT , $USER;

$id = required_param('id', PARAM_INT);

$experience = Experience::getExperience($id);

if(!Experience::checkExperience($id)) {
    throw new moodle_exception('invalidexperience', 'local_dta');
}

if(!$ourcase = OurCases::getCaseByExperience($id)) {
    $ourcase = OurCases::addCase($id , date("Y-m-d H:i:s") , $USER->id); 
}

$strings = get_strings(['community_header', 'community_title'], "local_dta");

// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/ourcases/add.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->community_title);

echo $OUTPUT->header();


$templateContext = [
    'experience' => $experience,
];

echo $OUTPUT->render_from_template('local_dta/ourcases/ourcases', $templateContext);

echo $OUTPUT->footer();
