<?php

/**
 * community page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../config.php');
require_login();


require_once(__DIR__ . './classes/experiences.php');
use local_dta\Experience;

global $CFG, $PAGE, $OUTPUT;

$strings = get_strings(['community_header' , 'community_title'], "local_dta");

// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/community.php'));
$PAGE->set_context( context_system::instance()) ;
$PAGE->set_title($strings->community_title);
$PAGE->set_heading($strings->community_header);

echo $OUTPUT->header();

$experiences = Experience::getAllExperiences(true);

print_object($experiences);


// echo $OUTPUT->render_from_template('local_dta/community/community', ["experiences" => $exampleCourses]);

echo $OUTPUT->footer();
