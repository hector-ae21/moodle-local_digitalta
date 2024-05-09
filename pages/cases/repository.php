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
 * Case repository page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/dta/classes/cases.php');
require_once($CFG->dirroot . '/local/dta/classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/filterutils.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/stringutils.php');

require_login();

use local_dta\Cases;
use local_dta\Components;
use local_dta\utils\FilterUtils;
use local_dta\utils\StringUtils;

global $CFG, $PAGE, $OUTPUT;

$strings = get_strings(['cases_header', 'cases_title'], 'local_dta');

// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/pages/cases/repository.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->cases_title);
$PAGE->requires->js_call_amd('local_dta/reactions/manager', 'init');

echo $OUTPUT->header();

// Get the cases
$cases = Cases::get_all_cases(true, 1);

// Get the user data
$user = get_complete_user_data('id', $USER->id);
$picture = new user_picture($user);
$picture->size = 101;
$user->imageurl = $picture->get_url($PAGE)->__toString();

$templateContext = [
    'user' => $user,
    'component' => 'case',
    'cases' => array_values($cases),
    'url_create_case' => $CFG->wwwroot . '/local/dta/pages/cases/manage.php',
    'url_case' => $CFG->wwwroot . '/local/dta/pages/cases/view.php?id=',
    'themepixurl' => $CFG->wwwroot . '/theme/dta/pix/',
];

$templateContext = FilterUtils::apply_filter_to_template_object($templateContext);

echo $OUTPUT->render_from_template('local_dta/cases/repository/repository', $templateContext);

echo $OUTPUT->footer();
