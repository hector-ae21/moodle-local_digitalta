<?php

/**
 * community page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/ourcases.php');
require_once(__DIR__ . './../../classes/utils/string_utils.php');
require_once(__DIR__ . './../../classes/reactions.php');
require_once(__DIR__ . './../../classes/constants.php');

require_login();

use local_dta\OurCases;
use local_dta\utils\StringUtils;
use local_dta\CONSTANTS;

global $CFG, $PAGE, $OUTPUT;

$strings = get_strings(['ourcases_header', 'ourcases_title'], "local_dta");

// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/pages/cases/repository.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->ourcases_title);
$PAGE->requires->js_call_amd('local_dta/reactions/manager', 'init');

echo $OUTPUT->header();

$cases = OurCases::get_active_cases();
$full_cases = array();

if (!empty($cases)) {
    $full_cases = array_values(array_map(function ($case) {
        $object = OurCases::get_section_header($case->id);
        $object->description = StringUtils::truncateHtmlText($object->description, 500);
        $object->timecreated = $case->timecreated;
        $object->user = $case->user;
        $object->pictureurl = $case->pictureurl;
        $object->reactions = $case->reactions;
        return $object;
    }, $cases));
}

$user = get_complete_user_data("id", $USER->id);
$picture = new user_picture($user);
$picture->size = 101;
$user->imageurl = $picture->get_url($PAGE)->__toString();


$templateContext = [
    "user" => $user,
    "instance" => CONSTANTS::REACTIONS_INSTANCES['CASE'],
    "cases" => array_values($full_cases),
    "url_create_case" => $CFG->wwwroot . '/local/dta/pages/cases/manage.php',
    "url_case" => $CFG->wwwroot . '/local/dta/pages/cases/view.php?id=',
    "themepixurl" => $CFG->wwwroot . "/theme/dta/pix/",
];



echo $OUTPUT->render_from_template('local_dta/cases/repository', $templateContext);

echo $OUTPUT->footer();
