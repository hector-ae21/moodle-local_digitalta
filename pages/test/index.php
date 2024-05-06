<?php

/**
 * community page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once (__DIR__ . '/../../../../config.php');
require_once (__DIR__ . './../../classes/resource.php');
require_once (__DIR__ . './../../classes/utils/string_utils.php');
require_once (__DIR__ . './../../classes/utils/filter_utils.php');

require_login();



global $CFG, $PAGE, $OUTPUT;

$strings = get_strings(['test_header', 'test_title'], "local_dta");

// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/pages/test/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->test_title);

echo $OUTPUT->header();


$template_context = [
];


echo $OUTPUT->render_from_template('local_dta/test/index', $template_context);

echo $OUTPUT->footer();
