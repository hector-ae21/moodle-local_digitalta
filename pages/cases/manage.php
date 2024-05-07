<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Case manage page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/dta/classes/cases.php');
require_once($CFG->dirroot . '/local/dta/classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/experiences.php');
require_once($CFG->dirroot . '/local/dta/classes/sections.php');
require_once($CFG->dirroot . '/local/dta/classes/tinyeditorhandler.php');

use local_dta\Cases;
use local_dta\Components;
use local_dta\Experiences;
use local_dta\Sections;
use local_dta\TinyEditorHandler;

use Exception;
use stdClass;

require_login();

global $CFG, $PAGE, $OUTPUT, $USER;

$experienceid = optional_param('id', 0, PARAM_INT);
$caseid       = optional_param('caseid', 0, PARAM_INT);
$casetitle    = optional_param('casetitle', 0, PARAM_RAW);

$strings = get_strings(['ourcases_header', 'ourcases_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/cases/manage.php', ['id' => $experienceid]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->ourcases_title);
$PAGE->requires->js_call_amd(
    'local_dta/cases/manage/form',
    'init',
    array(
        'url_view' => $CFG->wwwroot . '/local/dta/pages/cases/view.php?id=', 
    )
);

echo $OUTPUT->header();

(new TinyEditorHandler)->get_config_editor(['maxfiles' =>1]);


if ($experienceid) {
    $template = 'local_dta/cases/manage/with-experience';
    if (!$experience = Experiences::get_experience($experienceid)) {
        throw new moodle_exception('invalidexperience', 'local_dta');
    }
    $case               = new stdClass();
    $case->experienceid = $experienceid;
    $case->title        = $experience->title;
    $case->lang         = $experience->lang;
    $case = Cases::add_case($case);
} elseif ($caseid or $casetitle) {
    $template = 'local_dta/cases/manage/without-experience';
    if (!$case = Cases::get_case($caseid)) {
        $case               = new stdClass();
        $case->title        = $casetitle;
        $case->lang         = 'en'; // HARDCODED
        $case = Cases::add_case($case);
    };
} else {
    throw new Exception('Invalid parameters');
}

$sections = Sections::get_sections([
    'component' => [Components::get_component_by_name('case')->id],
    'componentinstance' => [$case->id]
]);

$sectionheader = [
    'title' => $case->title,
    'description' => '' // TODO SECTIONS
];

$template_context = [
    'experience' => $experience ?? null,
    'sectionheader' => $sectionheader,
    'sections' => $sections,
    'case' => $case,
];

echo $OUTPUT->render_from_template($template, $template_context);

echo $OUTPUT->footer();
