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
require_once(__DIR__ . './../../classes/tiny_editor_handler.php');

use local_dta\Experience;
use local_dta\OurCases;
use local_dta\tiny_editor_handler;

require_login();

global $CFG, $PAGE, $OUTPUT, $USER;

$experienceid = optional_param('id', 0, PARAM_INT);
$case = optional_param('caseid', 0, PARAM_INT);
$case_title = optional_param('casetitle', 0, PARAM_RAW);

$strings = get_strings(['ourcases_header', 'ourcases_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/cases/manage.php', ['id' => $experienceid]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->ourcases_title);
$PAGE->requires->js_call_amd(
    'local_dta/cases/manage/form',
    'init',
    array(
        'url_view' => $CFG->wwwroot . '/local/dta/pages/cases/repository.php', 
    )
);

echo $OUTPUT->header();

(new tiny_editor_handler)->get_config_editor(['maxfiles' =>1]);


if ($experienceid) {
    // IF EXPERIENCE EXISTS
    if (!$experience = Experience::get_experience($experienceid)) {
        throw new moodle_exception('invalidcases', 'local_dta');
    }

    if (!$ourcase = OurCases::get_case_by_experience($experienceid)) {
        $ourcase = OurCases::add_with_experience($experienceid, date("Y-m-d H:i:s"), $USER->id);
    }

    $sections = array_values(OurCases::get_sections_text($ourcase->id));

    if (!$section_header = OurCases::get_section_header($ourcase->id)) {
        throw new moodle_exception('invalidcasessection', 'local_dta');
    }

    $templateContext = [
        'experience' => $experience,
        'sectionheader' => $section_header,
        'sections' => $sections,
        'ourcase' => $ourcase,
    ];

    echo $OUTPUT->render_from_template('local_dta/cases/manage-with-experience', $templateContext);
} elseif ($case) {
    // IF CASE EXISTS
    if (!$ourcase = OurCases::get_case($case)) {
        throw new moodle_exception('invalidcases', 'local_dta');
    };
    if ($case_title) $section_header->title = $case_title;

    $sections = array_values(OurCases::get_sections_text($ourcase->id));
    $section_header = OurCases::get_section_header($ourcase->id);
    $templateContext = [
        'sectionheader' => $section_header,
        'sections' => $sections,
        'ourcase' => $ourcase,
    ];


    echo $OUTPUT->render_from_template('local_dta/cases/manage-without-experience', $templateContext);
} else {
    // IF NO EXPERIENCE OR CASE
    $ourcase = OurCases::add_without_experience(date("Y-m-d H:i:s"), $USER->id);

    if (!$section_header = OurCases::get_section_header($ourcase->id)) {
        throw new moodle_exception('invalidcasessection', 'local_dta');
    }
    if ($case_title) $section_header->title = $case_title;

    $sections = array_values(OurCases::get_sections_text($ourcase->id));
    $section_header->title = format_text($section_header->title, FORMAT_HTML, ['filter' => true]);
    $templateContext = [
        'sectionheader' => $section_header,
        'sections' => $sections,
        'ourcase' => $ourcase,
    ];

    echo $OUTPUT->render_from_template('local_dta/cases/manage/without-experience', $templateContext);
}



echo $OUTPUT->footer();
