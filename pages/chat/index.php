<?php

/**
 * profile 
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');

require_login();

global $CFG, $PAGE, $OUTPUT , $USER;


$PAGE->set_url(new moodle_url('/local/dta/pages/mentors/index.php'));
$PAGE->set_context(context_system::instance()) ;
$PAGE->set_title("Mentors");

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_dta/chat/index', []);

echo $OUTPUT->footer();

