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
 * Experience reflection view page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/dta/classes/experiences.php');
require_once($CFG->dirroot . '/local/dta/classes/reflection.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/filterutils.php');

use local_dta\Experiences;
use local_dta\Reflection;
use local_dta\utils\FilterUtils;


require_login();

global $CFG, $PAGE, $OUTPUT , $USER;

// Seting the page url and context
$id = required_param('id', PARAM_INT);
$PAGE->set_url(new moodle_url('/local/dta/pages/experiences/reflection/view.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());

// Get the experience
if(!$experience = Experiences::get_experience($id)) {
    throw new moodle_exception('invalidexperience', 'local_dta');
}

$reflection = Reflection::get_by_experience($id);

// Get reflection sections
$reflection->sections = Reflection::get_sections_by_groups($reflection->id,"ALL");

echo $OUTPUT->header();

$template_context = [
    'reflection' => $reflection,  
];

$template_context = FilterUtils::apply_filter_to_template_object($template_context);

echo $OUTPUT->render_from_template('local_dta/experiences/reflection/view', $template_context);

echo $OUTPUT->footer();
