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
 * Version metadata for the local_dta plugin.
 *
 * @package   local_dta
 * @copyright 2024 Salvador Banderas Rovira
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/experience.php');
require_once(__DIR__ . './../../classes/ourcases.php');
require_once(__DIR__ . './../../classes/context.php');
require_once(__DIR__ . './../../classes/themes.php');
require_once(__DIR__ . './../../classes/tags.php');
require_once(__DIR__ . './../../classes/utils/string_utils.php');
require_once(__DIR__ . './../../classes/utils/filter_utils.php');

$tagtype = required_param('type', PARAM_TEXT);
$tagid = required_param('id', PARAM_INT);

require_login();

use local_dta\Context;
use local_dta\Experience;
use local_dta\OurCases;
use local_dta\Themes;
use local_dta\Tags;
use local_dta\utils\StringUtils;
use local_dta\utils\filter_utils;

global $CFG, $PAGE, $OUTPUT;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/dta/pages/tags/index.php', ['type' => $tagtype, 'id' => $tagid]));

$tagtheme = ($tagtype == 'tag')
    ? Tags::get_tag($tagid)
    : Themes::get_theme($tagid);
if (!$tagtheme) {
    throw new moodle_exception('invalidthemetag', 'local_dta');
}
$pagetitle = ($tagtype == 'tag')
    ? get_string('themestags_tag', 'local_dta')
    : get_string('themestags_theme', 'local_dta');

$PAGE->set_title($pagetitle . ': ' . $tagtheme->name);

// Get the experiences
$experiences = Context::get_contexts_by_modifier($tagtype, $tagid, 'experience');
$experiences = array_values($experiences);
$experiences = array_map(function($key, $context) {
    $experience = Experience::get_experience($context->componentinstance);
    $experience->description = StringUtils::truncateHtmlText($experience->description);
    return $experience;
}, array_keys($experiences), $experiences);

// Get the cases
$cases = Context::get_contexts_by_modifier($tagtype, $tagid, 'case');
$cases = array_values($cases);
$cases = array_map(function($key, $context) {
    $case = OurCases::get_case($context->componentinstance);
    $caseText = OurCases::get_sections_text($case->id, true);
    $case->casetext = array_values($caseText)[0];
    $case->casetext->description = str_replace("<br>",
        " ",
        StringUtils::truncateHtmlText($case->casetext->description, 100));
    return $case;
}, array_keys($cases), $cases);

// Get the users
// TODO
$users = Context::get_contexts_by_modifier($tagtype, $tagid, 'user');
$users = array_values($users);

echo $OUTPUT->header();

$template_context = [
    "title" => $pagetitle,
    "themepixurl" => $CFG->wwwroot . "/theme/dta/pix/",
    "tag" => [
        "id" => $tagtheme->id,
        "name" => $tagtheme->name
    ],
    "experiences" => [
        "data" => $experiences,
        "viewurl" => $CFG->wwwroot . '/local/dta/pages/experiences/view.php?id='
    ],
    "cases" => [
        "data" => $cases,
        "viewurl" => $CFG->wwwroot . '/local/dta/pages/cases/view.php?id='
    ],
    "users" => [
        "data" => $users,
        "viewurl" => $CFG->wwwroot . '/local/dta/pages/profile/index.php?id='
    ]
];

$template_context = filter_utils::apply_filter_to_template_object($template_context);

echo $OUTPUT->render_from_template('local_dta/tags/view/view', $template_context);

echo $OUTPUT->footer();
