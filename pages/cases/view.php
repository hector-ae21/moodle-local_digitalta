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
 * Case view page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/dta/classes/cases.php');
require_once($CFG->dirroot . '/local/dta/classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/reactions.php');
require_once($CFG->dirroot . '/local/dta/classes/sections.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/filterutils.php');

use local_dta\Cases;
use local_dta\Components;
use local_dta\Reactions;
use local_dta\Sections;
use local_dta\utils\FilterUtils;

require_login();

global $CFG, $PAGE, $OUTPUT, $USER;

$id = optional_param('id', 0, PARAM_INT);

$strings = get_strings(['cases_header', 'cases_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/cases/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->cases_title);
$PAGE->requires->js_call_amd('local_dta/reactions/manager', 'init');

echo $OUTPUT->header();

if (!$case = Cases::get_case($id)) {
    throw new moodle_exception('invalidcases', 'local_dta');
}

$case->reactions = Reactions::get_reactions_for_render_case($case->id);

$sections = Sections::get_sections([
    'component' => [Components::get_component_by_name('case')->id],
    'componentinstance' => [$case->id]
]);

$sectionheader = [
    'title' => $case->title,
    'description' => '' // SECTIONS TODO
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

$templateContext = FilterUtils::apply_filter_to_template_object($templateContext);

echo $OUTPUT->render_from_template('local_dta/cases/view/view', $templateContext);

echo $OUTPUT->footer();
