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
 * Teacher Academy dashboard page
 *
 * @package   local_digitalta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/digitalta/classes/cases.php');
require_once($CFG->dirroot . '/local/digitalta/classes/components.php');
require_once($CFG->dirroot . '/local/digitalta/classes/experiences.php');
require_once($CFG->dirroot . '/local/digitalta/classes/reactions.php');
require_once($CFG->dirroot . '/local/digitalta/classes/tags.php');
require_once($CFG->dirroot . '/local/digitalta/classes/themes.php');
require_once($CFG->dirroot . '/local/digitalta/classes/tinyeditorhandler.php');
require_once($CFG->dirroot . '/local/digitalta/classes/files/filemanagerhandler.php');

require_login();

use local_digitalta\Cases;
use local_digitalta\Components;
use local_digitalta\Experiences;
use local_digitalta\Reactions;
use local_digitalta\Tags;
use local_digitalta\Themes;
use local_digitalta\TinyEditorHandler;

$PAGE->set_url(new moodle_url('/local/digitalta/pages/teacheracademy/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('teacheracademy:title', 'local_digitalta'));
$PAGE->requires->js_call_amd('local_digitalta/teacheracademy/actions', 'init');
$PAGE->requires->js_call_amd('local_digitalta/reactions/main', 'init');
$PAGE->requires->js_call_amd('local_digitalta/commun/translate', 'translateButton');

echo $OUTPUT->header();

(new TinyEditorHandler)->get_config_editor(['maxfiles' => 1]);

// Get themes
$themes = Themes::get_themes();
$themes = array_slice($themes, 0, 8, true);
$themes = array_map(function ($key, $theme) {
    global $OUTPUT;
    $theme->picture = $OUTPUT->image_url('digitalta-theme' . ($key + 1) . '-colored', 'local_digitalta');
    return $theme;
}, array_keys($themes), $themes);

// Get tags
$tags = Tags::get_tags();

// Get experiences
$featuredExperiences = Reactions::get_most_liked_component(
    Components::get_component_by_name('experience')->id,
    9
);

$neededExperiences = 9 - count($featuredExperiences);

$featuredExperiencesIds = array_map(function ($experience) {
    return $experience->componentinstance;
}, $featuredExperiences);

$experiences = Experiences::get_experiences(['visible' => 1], true, 'timecreated DESC', 9);

$experiences = array_filter($experiences, function ($experience) use ($featuredExperiencesIds) {
    return !in_array($experience->id, $featuredExperiencesIds);
});

$featuredExperiencesData = array_map(function ($experienceId) {
    return Experiences::get_experience($experienceId);
}, $featuredExperiencesIds);

$experiences = array_merge($featuredExperiencesData, array_slice($experiences, 0, $neededExperiences));



$featuredCases = Reactions::get_most_liked_component(
    Components::get_component_by_name('case')->id,
    9
);
$neededCases = 4 - count($featuredCases);

$featuredCasesIds = array_map(function ($case) {
    return $case->componentinstance;
}, $featuredCases);

$cases = Cases::get_cases(['status' => 1], true, 'timecreated DESC', 4);

$cases = array_filter($cases, function ($case) use ($featuredCasesIds) {
    return !in_array($case->id, $featuredCasesIds);
});

$featuredCasesData = array_map(function ($caseId) {
    return Cases::get_case($caseId);
}, $featuredCasesIds);

$cases = array_merge($featuredCasesData, array_slice($cases, 0, $neededCases));

$needstranslation = array_reduce(array_merge($experiences, $cases), function ($carry, $item) {
    return $carry || strtolower(current_language()) != strtolower($item->lang);
}, false);

// Get user data
$user = get_complete_user_data('id', $USER->id);
$picture = new user_picture($user);
$picture->size = 101;
$user->imageurl = $picture->get_url($PAGE)->__toString();

// Get the actions
$actions = [
    [
        'string' => get_string('teacheracademy:actions:explore', 'local_digitalta'),
        'action' => 'open_url',
        'value' => $CFG->wwwroot . '/local/digitalta/pages/experiences/index.php'
    ],
    [
        'string' => get_string('teacheracademy:actions:ask', 'local_digitalta'),
        'action' => 'modal_ask',
        'value' => null
    ],
    [
        'string' => get_string('teacheracademy:actions:share', 'local_digitalta'),
        'action' => 'modal_share',
        'value' => null
    ],
    [
        'string' => get_string('teacheracademy:actions:connect', 'local_digitalta'),
        'action' => 'open_url',
        'value' => $CFG->wwwroot . '/local/digitalta/pages/tutors/index.php'
    ],
    [
        'string' => get_string('teacheracademy:actions:discover', 'local_digitalta'),
        'action' => 'open_url',
        'value' => $CFG->wwwroot . '/local/digitalta/pages/resources/index.php'
    ],
    [
        'string' => get_string('teacheracademy:actions:getinspired', 'local_digitalta'),
        'action' => 'open_url',
        'value' => $CFG->wwwroot . '/local/digitalta/pages/cases/index.php'
    ],
    [
        'string' => get_string('teacheracademy:actions:create', 'local_digitalta'),
        'action' => 'open_url',
        'value' => $CFG->wwwroot . '/local/digitalta/pages/cases/manage.php?casetitle=' . get_string('cases:manage:new', 'local_digitalta')
    ]
];

// Template context
$templateContext = [
    'user' => $user,
    'actions' => $actions,
    'mainvideourl' => $CFG->wwwroot . '/theme/digitalta/statics/main-video.mp4',
    'experiences' => [
        'data' => $experiences,
        'viewurl' => $CFG->wwwroot . '/local/digitalta/pages/experiences/view.php?id=',
        'allurl' => $CFG->wwwroot . '/local/digitalta/pages/experiences/index.php',
    ],
    'themes' => [
        'data' => $themes,
        'viewurl' => $CFG->wwwroot . '/local/digitalta/pages/tags/view.php?type=theme&id=',
        'allurl' => $CFG->wwwroot . '/local/digitalta/pages/tags/index.php'
    ],
    'cases' => [
        'data' => array_slice($cases, 0, 4),
        'viewurl' => $CFG->wwwroot . '/local/digitalta/pages/cases/view.php?id=',
        'allurl' => $CFG->wwwroot . '/local/digitalta/pages/cases/index.php'
    ],
    'needstranslation' => $needstranslation,
    'wwwroot' => $CFG->wwwroot,
];

echo $OUTPUT->render_from_template('local_digitalta/teacheracademy/dashboard', $templateContext);

echo $OUTPUT->footer();
