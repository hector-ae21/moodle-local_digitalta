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

    if ($oldversion < 2024031307) {
        // Define table digital_cases_likes to be created.
        $table = new xmldb_table('digital_case_likes');

        // Adding fields to table digital_cases_likes.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', ['unsigned' => true, 'notnull' => true, 'autoincrement' => true, 'sequence' => true]);
        $table->add_field('caseid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('reactiontype', XMLDB_TYPE_INTEGER, '1', ['unsigned' => false, 'notnull' => false]);

        // Adding keys to table digital_cases_likes.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('caseid', XMLDB_KEY_FOREIGN, ['caseid'], 'digital_ourcases', ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Conditionally launch create table for digital_cases_likes.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table digital_cases_comments to be created.
        $table = new xmldb_table('digital_case_comments');

        // Adding fields to table digital_cases_comments.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true, 'autoincrement' => true, 'sequence' => true]);
        $table->add_field('caseid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('comment', XMLDB_TYPE_TEXT, null, ['notnull' => true]);

        // Adding keys to table digital_cases_comments.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('caseid', XMLDB_KEY_FOREIGN, ['caseid'], 'digital_ourcases', ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Conditionally launch create table for digital_cases_comments.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // DTA savepoint reached.
        upgrade_plugin_savepoint(true, 2024031306, 'local', 'dta');
    }

    if ($oldversion < 2024032001) {

        // Define table digital_cases_comments to be created.
        $table = new xmldb_table('digital_experience_report');

        // Adding fields to table digital_cases_likes.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', ['unsigned' => true, 'notnull' => true, 'autoincrement' => true, 'sequence' => true]);
        $table->add_field('experienceid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);

        // Adding keys to table digital_cases_likes.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('experienceid', XMLDB_KEY_FOREIGN, ['experienceid'], 'digital_experiences', ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Conditionally launch create table for digital_cases_comments.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table digital_cases_comments to be created.
        $table = new xmldb_table('digital_case_report');

        // Adding fields to table digital_cases_likes.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', ['unsigned' => true, 'notnull' => true, 'autoincrement' => true, 'sequence' => true]);
        $table->add_field('caseid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);

        // Adding keys to table digital_cases_likes.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('caseid', XMLDB_KEY_FOREIGN, ['caseid'], 'digital_ourcases', ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Conditionally launch create table for digital_cases_comments.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // DTA savepoint reached.
        upgrade_plugin_savepoint(true, 2024032001, 'local', 'dta');
    }

    if ($oldversion < 2024032600) {

        // Table for storing reflections
        $table = new xmldb_table('digital_reflection');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true, 'autoincrement' => true, 'sequence' => true]);
        $table->add_field('experienceid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => false]);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('timecreated', XMLDB_TYPE_DATETIME, null, ['notnull' => true]);
        $table->add_field('timemodified', XMLDB_TYPE_DATETIME, null, ['notnull' => true]);
        $table->add_field('status', XMLDB_TYPE_INTEGER, '1', ['notnull' => true, 'default' => 0]);
        $table->add_field('visible', XMLDB_TYPE_INTEGER, '1', ['notnull' => true, 'default' => 0]);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('experienceid', XMLDB_KEY_FOREIGN, ['experienceid'], 'digital_experiences', ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']); // Ensure 'user' table is correctly referenced

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Table for storing reflection sections
        $table = new xmldb_table('digital_refl_sections');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true, 'autoincrement' => true, 'sequence' => true]);
        $table->add_field('reflectionid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('groupid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('sequence', XMLDB_TYPE_INTEGER, '11', ['notnull' => true]);
        $table->add_field('sectiontype', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('contentid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('timecreated', XMLDB_TYPE_DATETIME, null, ['notnull' => true]);
        $table->add_field('timemodified', XMLDB_TYPE_DATETIME, null, ['notnull' => true]);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('reflectionid', XMLDB_KEY_FOREIGN, ['reflectionid'], 'digital_reflection', ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Table for storing text section content
        $table = new xmldb_table('digital_refl_sec_text');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true, 'autoincrement' => true, 'sequence' => true]);
        $table->add_field('content', XMLDB_TYPE_TEXT, null, ['notnull' => true]);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Savepoint reached
        upgrade_plugin_savepoint(true, 2024032600, 'local', 'dta');
    }

    if ($oldversion < 2024040302) {

        // Table for storing cases section content
        $table = new xmldb_table('digital_refl_sec_cases');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true, 'autoincrement' => true, 'sequence' => true]);
        $table->add_field('caseid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2024040302, 'local', 'dta');
    }

    if ($oldversion < 2024042600) {

        // Define table digital_themes to be created.
        $table = new xmldb_table('digital_themes');

        // Adding fields to table digital_themes.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table digital_themes.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for digital_themes.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table digital_themes_context to be created.
        $table = new xmldb_table('digital_themes_context');

        // Adding fields to table digital_themes_context.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('type', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('instance', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('theme', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        
        // Adding keys to table digital_themes_context.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('theme', XMLDB_KEY_FOREIGN, ['theme'], 'digital_themes', ['id']);
        $table->add_key('typeinstancetheme', XMLDB_KEY_UNIQUE, ['type', 'instance', 'theme']);

        // Conditionally launch create table for digital_themes_context.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Dta savepoint reached.
        upgrade_plugin_savepoint(true, 2024042600, 'local', 'dta');

    }

    if ($oldversion < 2024043000) {
        // Table for storing resources
        $table = new xmldb_table('digital_resources');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'id');
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'name');
        $table->add_field('type', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'description');
        $table->add_field('path', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'type');
        $table->add_field('lang', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'path');
        $table->add_field('timecreated', XMLDB_TYPE_DATETIME, null, ['notnull' => true]);
        $table->add_field('timemodified', XMLDB_TYPE_DATETIME, null, ['notnull' => true]);


        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);


        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2024043000, 'local', 'dta');
    }

    if ($oldversion < 2024050200) {

        // Add new fields to digital_tags table
        $table = new xmldb_table('digital_tags');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        if (!$dbman->field_exists($table, 'timecreated')) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        if (!$dbman->field_exists($table, 'timemodefied')) {
            $dbman->add_field($table, $field);
        }

        // Define table digital_components to be created.
        $table = new xmldb_table('digital_components');

        // Adding fields to table digital_components.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table digital_components.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('name', XMLDB_KEY_UNIQUE, ['name']);

        // Conditionally launch create table for digital_components.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Insert the components
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
        $local_dta_components = $DB->get_records('digital_components');
        $local_dta_components = array_column($local_dta_components, 'id', 'name');

        // Define table digital_modifiers to be created.
        $table = new xmldb_table('digital_modifiers');

        // Adding fields to table digital_modifiers.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table digital_modifiers.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('name', XMLDB_KEY_UNIQUE, ['name']);

        // Conditionally launch create table for digital_modifiers.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Insert the modifiers
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
        $local_dta_modifiers = $DB->get_records('digital_modifiers');
        $local_dta_modifiers = array_column($local_dta_modifiers, 'id', 'name');

        // Insert the themes
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
        $local_dta_themes = $DB->get_records('digital_themes');
        $local_dta_themes = array_column($local_dta_themes, 'id', 'name');

        // Define table digital_context to be created.
        $table = new xmldb_table('digital_context');

        // Adding fields to table digital_context.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('component', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('componentinstance', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('modifier', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('modifierinstance', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table digital_context.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('componentmodifier', XMLDB_KEY_UNIQUE, ['component', 'componentinstance', 'modifier', 'modifierinstance']);
        $table->add_key('component', XMLDB_KEY_FOREIGN, ['component'], 'digital_components', ['id']);
        $table->add_key('modifier', XMLDB_KEY_FOREIGN, ['modifier'], 'digital_modifiers', ['id']);

        // Conditionally launch create table for digital_context.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Migrate content from digital_themes_context to digital_context
        $table = new xmldb_table('digital_themes_context');
        if ($dbman->table_exists($table)) {
            $current_themes_context_records = $DB->get_records('digital_themes_context');
            foreach ($current_themes_context_records as $current_themes_context_record) {
                if ($DB->record_exists('digital_context', [
                        'component' => $local_dta_components[strtolower($current_themes_context_record->type)],
                        'componentinstance' => $current_themes_context_record->instance,
                        'modifier' => $local_dta_modifiers['theme'],
                        'modifierinstance' => $current_themes_context_record->theme])) {
                    continue;
                }
                $context = new stdClass();
                $context->component = $local_dta_components[strtolower($current_themes_context_record->type)];
                $context->componentinstance = $current_themes_context_record->instance;
                $context->modifier = $local_dta_modifiers['theme'];
                $context->modifierinstance = $current_themes_context_record->theme;
                $context->timecreated = time();
                $context->timemodified = time();
                $DB->insert_record('digital_context', $context);
            }
            unset($current_themes_context_records, $current_themes_context_record);
            $dbman->drop_table($table);
        }

        // Migrate content from digital_experience_tag to digital_context
        $table = new xmldb_table('digital_experience_tag');
        if ($dbman->table_exists($table)) {
            $current_experience_tag_records = $DB->get_records('digital_experience_tag');
            foreach ($current_experience_tag_records as $current_experience_tag_record) {
                if ($DB->record_exists('digital_context', [
                        'component' => $local_dta_components['experience'],
                        'componentinstance' => $current_experience_tag_record->experienceid,
                        'modifier' => $local_dta_modifiers['tag'],
                        'modifierinstance' => $current_experience_tag_record->tagid])) {
                    continue;
                }
                $context = new stdClass();
                $context->component = $local_dta_components['experience'];
                $context->componentinstance = $current_experience_tag_record->experienceid;
                $context->modifier = $local_dta_modifiers['tag'];
                $context->modifierinstance = $current_experience_tag_record->tagid;
                $context->timecreated = time();
                $context->timemodified = time();
                $DB->insert_record('digital_context', $context);
            }
            unset($current_experience_tag_records, $current_experience_tag_record);
            $dbman->drop_table($table);
        }

        // Migrate content from digital_cases_tag to digital_context
        $table = new xmldb_table('digital_cases_tag');
        if ($dbman->table_exists($table)) {
            $current_cases_tag_records = $DB->get_records('digital_cases_tag');
            foreach ($current_cases_tag_records as $current_cases_tag_record) {
                if ($DB->record_exists('digital_context', [
                        'component' => $local_dta_components['case'], 
                        'componentinstance' => $current_cases_tag_record->caseid,
                        'modifier' => $local_dta_modifiers['tag'],
                        'modifierinstance' => $current_cases_tag_record->tagid])) {
                    continue;
                }
                $context = new stdClass();
                $context->component = $local_dta_components['case'];
                $context->componentinstance = $current_cases_tag_record->caseid;
                $context->modifier = $local_dta_modifiers['tag'];
                $context->modifierinstance = $current_cases_tag_record->tagid;
                $context->timecreated = time();
                $context->timemodified = time();
                $DB->insert_record('digital_context', $context);
            }
            unset($current_cases_tag_records, $current_cases_tag_record);
            $dbman->drop_table($table);
        }

        // Dta savepoint reached.
        upgrade_plugin_savepoint(true, 2024050200, 'local', 'dta');
    }

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
    printf("<pre>%s</pre>", print_r($local_dta_components, true));

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
    $local_dta_modifiers = $DB->get_records('digital_modifiers');
    $local_dta_modifiers = array_column($local_dta_modifiers, 'id', 'name');
    printf("<pre>%s</pre>", print_r($local_dta_modifiers, true));

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
    $local_dta_themes = $DB->get_records('digital_themes');
    $local_dta_themes = array_column($local_dta_themes, 'id', 'name');
    printf("<pre>%s</pre>", print_r($local_dta_themes, true));

    return true;
}
