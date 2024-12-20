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
 * Experiences dashboard page
 *
 * @package   local_digitalta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/digitalta/classes/experiences.php');
require_once($CFG->dirroot . '/local/digitalta/classes/sections.php');

$pagetitle = get_string('experiences:title', 'local_digitalta');
$PAGE->set_url(new moodle_url('/local/digitalta/pages/experiences/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($pagetitle);
$PAGE->requires->js_call_amd('local_digitalta/reactions/main', 'init');
$PAGE->requires->js_call_amd('local_digitalta/commun/main', 'init');
$PAGE->requires->js_call_amd('local_digitalta/commun/translate', 'translateButton');


echo $OUTPUT->header();


$template_context = [
    'title' => $pagetitle,
    'component' => 'experience',
    'experiences' => [
        'data' => [],
        'viewurl' => $CFG->wwwroot . '/local/digitalta/pages/experiences/view.php?id='
    ],
    'viewtagurl' => $CFG->wwwroot . '/local/digitalta/pages/tags/view.php?type=tag&id=',
    'viewthemeurl' => $CFG->wwwroot . '/local/digitalta/pages/tags/view.php?type=theme&id=',
    'needstranslation' => true
];

echo $OUTPUT->render_from_template('local_digitalta/experiences/dashboard/dashboard', $template_context);

echo $OUTPUT->footer();
