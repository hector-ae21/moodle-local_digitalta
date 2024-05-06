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
 *  Upgrade steps for the local_dta plugin.
 *
 * @package    local_dta
 * @copyright  2024 ADSDR-FUNIBER Scepter Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/locallib.php');

function xmldb_local_dta_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    // Try all insertions regardless of the version
    // Insert the components
    $table = new xmldb_table('digital_components');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DTA_COMPONENTS as $value) {
            if ($DB->record_exists('digital_components', ['name' => $value])) {
                continue;
            }
            $component = new stdClass();
            $component->name = $value;
            $component->timecreated = time();
            $component->timemodified = time();
            $DB->insert_record('digital_components', $component);
        }
    }
    $local_dta_components = $DB->get_records('digital_components');
    $local_dta_components = array_column($local_dta_components, 'id', 'name');

    // Insert the modifiers
    $table = new xmldb_table('digital_modifiers');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DTA_MODIFIERS as $value) {
            if ($DB->record_exists('digital_modifiers', ['name' => $value])) {
                continue;
            }
            $modifier = new stdClass();
            $modifier->name = $value;
            $modifier->timecreated = time();
            $modifier->timemodified = time();
            $DB->insert_record('digital_modifiers', $modifier);
        }
    }

    // Insert the themes
    $table = new xmldb_table('digital_themes');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DTA_THEMES as $value) {
            if ($DB->record_exists('digital_themes', ['name' => $value])) {
                continue;
            }
            $theme = new stdClass();
            $theme->name = $value;
            $theme->timecreated = time();
            $theme->timemodified = time();
            $DB->insert_record('digital_themes', $theme);
        }
    }

    // Insert the resource types
    $table = new xmldb_table('digital_resources_types');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DTA_RESOURCE_TYPES as $value) {
            if ($DB->record_exists('digital_resources_types', ['name' => $value])) {
                continue;
            }
            $resource_type = new stdClass();
            $resource_type->name = $value;
            $resource_type->timecreated = time();
            $resource_type->timemodified = time();
            $DB->insert_record('digital_resources_types', $resource_type);
        }
    }

    // Insert the resource formats
    $table = new xmldb_table('digital_resources_formats');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DTA_RESOURCE_FORMATS as $value) {
            if ($DB->record_exists('digital_resources_formats', ['name' => $value])) {
                continue;
            }
            $resource_format = new stdClass();
            $resource_format->name = $value;
            $resource_format->timecreated = time();
            $resource_format->timemodified = time();
            $DB->insert_record('digital_resources_formats', $resource_format);
        }
    }

    return true;
}
