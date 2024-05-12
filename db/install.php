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
 * Install steps for the local_dta plugin.
 *
 * @package    local_dta
 * @copyright  2024 ADSDR-FUNIBER Scepter Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/locallib.php');

/**
 * Install the local_dta plugin.
 */
function xmldb_local_dta_install() {
    global $DB;

    // Insert the components
    foreach (LOCAL_DTA_COMPONENTS as $value) {
        $component = new stdClass();
        $component->name = $value;
        $component->timecreated = time();
        $component->timemodified = time();
        $DB->insert_record('digital_components', $component);
    }

    // Insert the modifiers
    foreach (LOCAL_DTA_MODIFIERS as $value) {
        $modifier = new stdClass();
        $modifier->name = $value;
        $modifier->timecreated = time();
        $modifier->timemodified = time();
        $DB->insert_record('digital_modifiers', $modifier);
    }

    // Insert the themes
    foreach (LOCAL_DTA_THEMES as $value) {
        $theme = new stdClass();
        $theme->name = $value;
        $theme->timecreated = time();
        $theme->timemodified = time();
        $DB->insert_record('digital_themes', $theme);
    }

    // Insert the resource types
    foreach (LOCAL_DTA_RESOURCE_TYPES as $value) {
        $resource = new stdClass();
        $resource->name = $value;
        $resource->timecreated = time();
        $resource->timemodified = time();
        $DB->insert_record('digital_resources_types', $resource);
    }

    // Insert the resource formats
    foreach (LOCAL_DTA_RESOURCE_FORMATS as $value) {
        $resource = new stdClass();
        $resource->name = $value;
        $resource->timecreated = time();
        $resource->timemodified = time();
        $DB->insert_record('digital_resources_formats', $resource);
    }

    // Insert the section types
    foreach (LOCAL_DTA_SECTION_TYPES as $value) {
        $section = new stdClass();
        $section->name = $value;
        $section->timecreated = time();
        $section->timemodified = time();
        $DB->insert_record('digital_sections_types', $section);
    }

    // Insert the section groups
    foreach (LOCAL_DTA_SECTION_GROUPS as $value) {
        $section = new stdClass();
        $section->name = $value;
        $section->timecreated = time();
        $section->timemodified = time();
        $DB->insert_record('digital_sections_groups', $section);
    }

    // Insert the languages
    foreach (LOCAL_DTA_LANGUAGES as $key => $value) {
        $language = new stdClass();
        $language->name = $key;
        $language->timecreated = time();
        $language->timemodified = time();
        $DB->insert_record('digital_languages', $language);
    }
}
