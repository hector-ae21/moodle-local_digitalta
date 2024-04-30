<?php

/**
 * community page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once (__DIR__ . '/../../../../config.php');
require_once (__DIR__ . './../../classes/experience.php');
require_once (__DIR__ . './../../classes/utils/string_utils.php');
require_once (__DIR__ . './../../classes/utils/filter_utils.php');

require_login();

use local_dta\CONSTANTS;
use local_dta\Experience;
use local_dta\utils\StringUtils;
use local_dta\utils\filter_utils;

global $CFG, $PAGE, $OUTPUT;

$strings = get_strings(['repository_header', 'repository_title'], "local_dta");

// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/pages/repository/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->repository_title);

echo $OUTPUT->header();



$template_context = [
  
];

$template_context = filter_utils::apply_filter_to_template_object($template_context);

echo $OUTPUT->render_from_template('local_dta/repository/dashboard', $template_context);

echo $OUTPUT->footer();
