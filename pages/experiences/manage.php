<?php

/**
 * Reflection page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__ . './../../../../config.php');
require_once(__DIR__ . './../../classes/reflection.php');
require_once(__DIR__ . './../../classes/tiny_editor_handler.php');


use local_dta\tiny_editor_handler;
use local_dta\Reflection;

require_login();

global $CFG, $PAGE, $OUTPUT , $USER;

// Seting the page url and context
$PAGE->set_url(new moodle_url('/local/dta/pages/experiences/manage.php'));
$PAGE->set_context(context_system::instance());
// $PAGE->requires->js_call_amd('local_dta/myexperience/reflection/manage_reflection', 'init');
echo $OUTPUT->header();

// Set tiny configs in DOM
(new tiny_editor_handler)->get_config_editor(['maxfiles' => 1]);



$template_context = [

];

echo $OUTPUT->render_from_template('local_dta/experiences/manage/manage', $template_context);

echo $OUTPUT->footer();
