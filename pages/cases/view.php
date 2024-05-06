<?php

/**
 * ourcases view page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/cases.php');
require_once(__DIR__ . './../../classes/reactions.php');
require_once(__DIR__ . './../../classes/sections.php');
require_once(__DIR__ . './../../classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/filter_utils.php');

use local_dta\Cases;
use local_dta\Reaction;
use local_dta\Sections;
use local_dta\Components;
use local_dta\utils\filter_utils;

require_login();

global $CFG, $PAGE, $OUTPUT, $USER;

$id = optional_param('id', 0, PARAM_INT);

$strings = get_strings(['ourcases_header', 'ourcases_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/cases/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->ourcases_title);
$PAGE->requires->js_call_amd('local_dta/reactions/manager', 'init');


echo $OUTPUT->header();

if (!$case = Cases::get_case($id)) {
    throw new moodle_exception('invalidcases', 'local_dta');
}

$case->reactions = Reaction::get_reactions_for_render_case($case->id);

$sections = Sections::get_sections([
    'component' => [Components::get_component_by_name('case')->id],
    'componentinstance' => [$case->id]
]);

$sectionheader = [
    'title' => $case->title,
    'description' => '' // TODO SECTIONS
];

$user = get_complete_user_data("id", $case->userid);
$picture = new user_picture($user);
$picture->size = 101;
$user->imageurl = $picture->get_url($PAGE)->__toString();

$templateContext = [
    'instance' => Components::get_component_by_name('case')->id,
    'sections' => $sections,
    'sectionheader' => $sectionheader,
    'case' => $case,
    'editurl' => new moodle_url('/local/dta/pages/cases/manage.php', ['caseid' => $case->id]),
    'deleteurl' => new moodle_url('/local/dta/pages/cases/delete.php', ['id' => $case->id]),
    'user' => $user,
];

$templateContext = filter_utils::apply_filter_to_template_object($templateContext);

echo $OUTPUT->render_from_template('local_dta/cases/view/view', $templateContext);

echo $OUTPUT->footer();
