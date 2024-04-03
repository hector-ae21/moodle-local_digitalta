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



    return true;
}
