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
require_once(__DIR__ . './../../classes/themes.php');
require_once(__DIR__ . './../../classes/tags.php');
require_once(__DIR__ . './../../classes/utils/string_utils.php');
require_once(__DIR__ . './../../classes/utils/filter_utils.php');

require_login();

use local_dta\Themes;
use local_dta\Tags;
use local_dta\utils\StringUtils;
use local_dta\utils\filter_utils;

global $CFG, $PAGE, $OUTPUT;

$pagetitle = get_string('themestags_title', 'local_dta');
    
$PAGE->set_context(context_system::instance());
$PAGE->set_title($pagetitle);
$PAGE->set_url(new moodle_url('/local/dta/pages/tags/index.php'));

// Get the themes
$themes = Themes::get_themes();
$themes = array_values($themes);
$themes = array_map(function($key, $theme) {
    global $OUTPUT;
    $theme->picture = $OUTPUT->image_url('dta-theme' . ($key + 1) . '-colored', 'local_dta');
    return $theme;
}, array_keys($themes), $themes);

// Get the tags
$tags = Tags::get_tags();
$tags = array_values($tags);
$tags = array_map(function($key, $tag) {
    $tag->weight = Tags::calculate_tag_weight($tag);
    return $tag;
}, array_keys($tags), $tags);

echo $OUTPUT->header();

$template_context = [
    "title" => $pagetitle,
    "themes" => [
        "data" => $themes,
        "viewurl" => $CFG->wwwroot . '/local/dta/pages/tags/view.php?type=theme&id='
    ],
    "tags" => [
        "data" => $tags,
        "viewurl" => $CFG->wwwroot . '/local/dta/pages/tags/view.php?type=tag&id='
    ],
];

$template_context = filter_utils::apply_filter_to_template_object($template_context);

echo $OUTPUT->render_from_template('local_dta/tags/dashboard/dashboard', $template_context);

echo $OUTPUT->footer();
