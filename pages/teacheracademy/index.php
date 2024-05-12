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
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/dta/classes/cases.php');
require_once($CFG->dirroot . '/local/dta/classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/experiences.php');
require_once($CFG->dirroot . '/local/dta/classes/reactions.php');
require_once($CFG->dirroot . '/local/dta/classes/tags.php');
require_once($CFG->dirroot . '/local/dta/classes/themes.php');
require_once($CFG->dirroot . '/local/dta/classes/tinyeditorhandler.php');
require_once($CFG->dirroot . '/local/dta/classes/files/filemanagerhandler.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/filterutils.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/stringutils.php');

require_login();

use local_dta\Cases;
use local_dta\Components;
use local_dta\Experiences;
use local_dta\Reactions;
use local_dta\Tags;
use local_dta\Themes;
use local_dta\TinyEditorHandler;
use local_dta\utils\FilterUtils;
use local_dta\utils\StringUtils;

$PAGE->set_url(new moodle_url('/local/dta/pages/teacheracademy/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('teacheracademy:title', 'local_dta'));
$PAGE->requires->js_call_amd('local_dta/teacheracademy/actions', 'init');
$PAGE->requires->js_call_amd('local_dta/reactions/manager', 'init');

echo $OUTPUT->header();

(new TinyEditorHandler)->get_config_editor(['maxfiles' => 1]);

// Get themes
$themes = Themes::get_themes();
$themes = array_values($themes);
$themes = array_slice($themes, 0, 8, true);
$themes = array_map(function($key, $theme) {
    global $OUTPUT;
    $theme->picture = $OUTPUT->image_url('dta-theme' . ($key + 1) . '-colored', 'local_dta');
    return $theme;
}, array_keys($themes), $themes);

// Get tags
$tags = Tags::get_tags();
$tags = array_values($tags);

// Get experiences
$featuredExperiences = Reactions::get_most_liked_component(
    Components::get_component_by_name('experience')->id,
    5
);
$featuredExperiences = array_map(function($experience) {
    $experience = Experiences::get_extra_fields($experience);
    return $experience->id;
}, $featuredExperiences);
$experiences = Experiences::get_latest_experiences(9, false);
$experiences = array_map(function($experience) use ($featuredExperiences) {
    $experience->description = ""; // SECTIONS TODO
    $experience->featured = (in_array($experience->id, $featuredExperiences)) ? true : false;
    return $experience;
}, $experiences);
array_multisort(
    array_column($experiences, 'featured'), SORT_DESC,
    array_column($experiences, 'timecreated'), SORT_DESC,
    $experiences);

// Get cases
$cases = array_values(Cases::get_all_cases(true, 1));
$cases = array_map(function($case) {
    $case->description = ""; // SECTIONS TODO
    return $case;
}, $cases);

// Get user data
$user = get_complete_user_data('id', $USER->id);
$picture = new user_picture($user);
$picture->size = 101;
$user->imageurl = $picture->get_url($PAGE)->__toString();

// Get the actions
$actions = [
    [
        'string' => get_string('teacheracademy:actions:explore', 'local_dta'),
        'action' => 'open_url',
        'value' => $CFG->wwwroot . "/local/dta/pages/experiences/index.php"
    ],
    [
        'string' => get_string('teacheracademy:actions:ask', 'local_dta'),
        'action' => 'modal_ask',
        'value' => null
    ],
    [
        'string' => get_string('teacheracademy:actions:share', 'local_dta'),
        'action' => 'modal_share',
        'value' => null
    ],
    [
        'string' => get_string('teacheracademy:actions:connect', 'local_dta'),
        'action' => 'open_url',
        'value' => $CFG->wwwroot . "/local/dta/pages/mentors/index.php"
    ],
    [
        'string' => get_string('teacheracademy:actions:discover', 'local_dta'),
        'action' => 'open_url',
        'value' => $CFG->wwwroot . "/local/dta/pages/repository/index.php"
    ],
    [
        'string' => get_string('teacheracademy:actions:getinspired', 'local_dta'),
        'action' => 'open_url',
        'value' => $CFG->wwwroot . "/local/dta/pages/cases/index.php"
    ]
];

// Template context
$templateContext = [
    "user" => $user,
    "themepixurl" => $CFG->wwwroot . "/theme/dta/pix/",
    "actions" => $actions,
    "experiences" => [
        "data" => $experiences,
        "addurl" => $CFG->wwwroot . "/local/dta/pages/experiences/manage.php",
        "viewurl" => $CFG->wwwroot . '/local/dta/pages/experiences/view.php?id=',
        "allurl" => $CFG->wwwroot . "/local/dta/pages/experiences/index.php",
    ],
    "themes" => [
        "data" => $themes,
        "viewurl" => $CFG->wwwroot . '/local/dta/pages/tags/view.php?type=theme&id=',
        "allurl" => $CFG->wwwroot . "/local/dta/pages/tags/index.php"
    ],
    "cases" => [
        "data" => array_slice($cases, 0, 4),
        "viewurl" => $CFG->wwwroot . "/local/dta/pages/cases/view.php?id=",
        "allurl" => $CFG->wwwroot . "/local/dta/pages/cases/index.php"
    ]
];

$templateContext = FilterUtils::apply_filter_to_template_object($templateContext);

echo $OUTPUT->render_from_template('local_dta/teacheracademy/dashboard', $templateContext);

echo $OUTPUT->footer();
