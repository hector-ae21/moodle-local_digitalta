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
 *  Upgrade steps for the local_digitalta plugin.
 *
 * @package    local_digitalta
 * @copyright  2024 ADSDR-FUNIBER Scepter Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/digitalta/locallib.php');

/**
 * Upgrade the local_digitalta plugin.
 *
 * @param int $oldversion The version we are upgrading from.
 * @return bool
 */
function xmldb_local_digitalta_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2024060200) {
        throw new Exception('The version is too old. Continuing the upgrade process is not possible. Please, reinstall the plugin. Keep in mind that you will lose all the data.');
    }

    if ($oldversion < 2025020401) {

        // Define table digitalta_chat_read_status to be created.
        $table = new xmldb_table('digitalta_chat_read_status');

        // Adding fields to table digitalta_chat_read_status.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('messageid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('read_at', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table digitalta_chat_read_status.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('messageid', XMLDB_KEY_FOREIGN, ['messageid'], 'digitalta_chat_messages', ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
        $table->add_key('messagecomponent', XMLDB_KEY_UNIQUE, ['messageid', 'userid']);

        // Conditionally launch create table for digitalta_chat_read_status.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Digitalta savepoint reached.
        upgrade_plugin_savepoint(true, 2025020401, 'local', 'digitalta');
    }


    // Try all insertions regardless of the version
    // Insert the components
    $table = new xmldb_table('digitalta_components');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DIGITALTA_COMPONENTS as $value) {
            if ($DB->record_exists('digitalta_components', ['name' => $value])) {
                continue;
            }
            $component = new stdClass();
            $component->name = $value;
            $component->timecreated = time();
            $component->timemodified = time();
            $DB->insert_record('digitalta_components', $component);
        }
    }
    $local_digitalta_components = $DB->get_records('digitalta_components');
    $local_digitalta_components = array_column($local_digitalta_components, 'id', 'name');

    // Insert the modifiers
    $table = new xmldb_table('digitalta_modifiers');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DIGITALTA_MODIFIERS as $value) {
            if ($DB->record_exists('digitalta_modifiers', ['name' => $value])) {
                continue;
            }
            $modifier = new stdClass();
            $modifier->name = $value;
            $modifier->timecreated = time();
            $modifier->timemodified = time();
            $DB->insert_record('digitalta_modifiers', $modifier);
        }
    }

    // Insert the themes
    $table = new xmldb_table('digitalta_themes');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DIGITALTA_THEMES as $value) {
            if ($DB->record_exists('digitalta_themes', ['name' => $value])) {
                continue;
            }
            $theme = new stdClass();
            $theme->name = $value;
            $theme->timecreated = time();
            $theme->timemodified = time();
            $DB->insert_record('digitalta_themes', $theme);
        }
    }

    // Insert the resource types
    $table = new xmldb_table('digitalta_resources_types');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DIGITALTA_RESOURCE_TYPES as $value) {
            if ($DB->record_exists('digitalta_resources_types', ['name' => $value])) {
                continue;
            }
            $resource_type = new stdClass();
            $resource_type->name = $value;
            $resource_type->timecreated = time();
            $resource_type->timemodified = time();
            $DB->insert_record('digitalta_resources_types', $resource_type);
        }
    }

    // Insert the resource formats
    $table = new xmldb_table('digitalta_resources_formats');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DIGITALTA_RESOURCE_FORMATS as $value) {
            if ($DB->record_exists('digitalta_resources_formats', ['name' => $value])) {
                continue;
            }
            $resource_format = new stdClass();
            $resource_format->name = $value;
            $resource_format->timecreated = time();
            $resource_format->timemodified = time();
            $DB->insert_record('digitalta_resources_formats', $resource_format);
        }
    }

    // Insert the section types
    $table = new xmldb_table('digitalta_sections_types');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DIGITALTA_SECTION_TYPES as $value) {
            if ($DB->record_exists('digitalta_sections_types', ['name' => $value])) {
                continue;
            }
            $section_type = new stdClass();
            $section_type->name = $value;
            $section_type->timecreated = time();
            $section_type->timemodified = time();
            $DB->insert_record('digitalta_sections_types', $section_type);
        }
    }

    // Insert the section groups
    $table = new xmldb_table('digitalta_sections_groups');
    if ($dbman->table_exists($table)) {
        foreach (LOCAL_DIGITALTA_SECTION_GROUPS as $value) {
            if ($DB->record_exists('digitalta_sections_groups', ['name' => $value])) {
                continue;
            }
            $section_group = new stdClass();
            $section_group->name = $value;
            $section_group->timecreated = time();
            $section_group->timemodified = time();
            $DB->insert_record('digitalta_sections_groups', $section_group);
        }
    }

    // Create the new system roles
    foreach (LOCAL_DIGITALTA_ROLES as $role) {
        $role = (object) $role;
        // Check if the role already exists
        if ($DB->record_exists('role', ['shortname' => $role->shortname])) {
            continue;
        }
        // Check if the archetype role exists
        if (!$archetyperoleid = $DB->get_field('role', 'id', ['shortname' => $role->archetype])) {
            continue;
        }
        // Create the new role
        $roleid = create_role($role->name, $role->shortname, $role->description, $role->archetype);
        // Set the context levels where the role is allowed
        set_role_contextlevels($roleid, [CONTEXT_SYSTEM]);
        // Copy the default permissions from the archetype role
        $types = array('assign', 'override', 'switch', 'view');
        foreach ($types as $type) {
            $rolestocopy = get_default_role_archetype_allows($type, $role->archetype);
            foreach ($rolestocopy as $tocopy) {
                $functionname = "core_role_set_{$type}_allowed";
                $functionname($roleid, $tocopy);
            }
        }
        // Copy the default capabilities from the archetype role
        $sourcerole = $DB->get_record('role', array('id' => $archetyperoleid));
        role_cap_duplicate($sourcerole, $roleid);
    }

    // Create user profile fields category
    if (!$profile_field_category_id = $DB->get_field('user_info_category', 'id', ['name' => 'DigitalTA'])) {
        $profile_field_category            = new stdClass();
        $profile_field_category->name      = 'DigitalTA';
        $profile_field_category->sortorder = 1;
        $profile_field_category_id         = $DB->insert_record('user_info_category', $profile_field_category);
    }

    // Create user profile fields
    foreach (LOCAL_DIGITALTA_PROFILE_FIELDS as $profile_field) {
        $profile_field['shortname']  = 'digitalta_' . $profile_field['shortname'];
        $profile_field['categoryid'] = $profile_field_category_id;
        if ($DB->record_exists('user_info_field', ['shortname' => $profile_field['shortname']])) {
            continue;
        }
        $DB->insert_record('user_info_field', (object) $profile_field);
    }

    return true;
}
