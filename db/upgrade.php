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

    if ($oldversion < 2024031306) {
        // Define table digital_cases_likes to be created.
        $table = new xmldb_table('digital_cases_likes');

        // Adding fields to table digital_cases_likes.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', ['unsigned' => true, 'notnull' => true, 'autoincrement' => true]);
        $table->add_field('casesid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('reactiontype', XMLDB_TYPE_INTEGER, '1', ['unsigned' => false, 'notnull' => false]);

        // Adding keys to table digital_cases_likes.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('caseid', XMLDB_KEY_FOREIGN, ['casesid'], 'digital_ourcases', ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Conditionally launch create table for digital_cases_likes.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table digital_cases_comments to be created.
        $table = new xmldb_table('digital_cases_comments');

        // Adding fields to table digital_cases_comments.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true, 'autoincrement' => true]);
        $table->add_field('caseid', XMLDB_TYPE_INTEGER, '11', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', ['unsigned' => true, 'notnull' => true]);
        $table->add_field('comment', XMLDB_TYPE_TEXT, null, ['notnull' => true]);

        // Adding keys to table digital_cases_comments.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('casesid', XMLDB_KEY_FOREIGN, ['casesid'], 'digital_ourcases', ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Conditionally launch create table for digital_cases_comments.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // DTA savepoint reached.
        upgrade_plugin_savepoint(true, 2024031306, 'local', 'dta');
    }

    return true;
}
