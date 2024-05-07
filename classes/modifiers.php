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
 * Modifiers class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

require_once($CFG->dirroot . '/local/dta/locallib.php');

defined('MOODLE_INTERNAL') || die();

/**
 * This class is used to manage the modifiers of the plugin
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Modifiers
{
    /** @var string The table name for the modifiers. */
    private static $table = 'digital_modifiers';

    /**
     * Get all the modifiers
     *
     * @return array
     */
    public static function get_all_modifiers()
    {
        global $DB;
        return $DB->get_records(self::$table);
    }

    /**
     * Get the modifier by id
     *
     * @param  int         $id The modifier identifier
     * @return object|null The modifier
     */
    public static function get_modifier_by_id(int $id)
    {
        global $DB;
        return $DB->get_record(self::$table, ['id' => $id]);
    }

    /**
     * Get the modifier by name
     *
     * @param  string      $name The modifier name
     * @return object|null The modifier
     */
    public static function get_modifier_by_name(string $name)
    {
        global $DB;
        return $DB->get_record(self::$table, ['name' => $name]);
    }

    /**
     * Get the database record of an instance of a modifier
     *
     * @param  string      $modifier   The modifier name
     * @param  int         $instanceid  The instance identifier
     * @return object|null The instance record
     */
    public static function get_instance_record(string $modifier, int $instanceid)
    {
        global $DB;

        $dbman = $DB->get_manager();

        if ($dbman->table_exists('digital_' . $modifier)) {
            return $DB->get_record('digital_' . $modifier, ['id' => $instanceid]);
        } elseif ($dbman->table_exists('digital_' . $modifier . 's')) {
            return $DB->get_record('digital_' . $modifier . 's', ['id' => $instanceid]);
        } elseif ($dbman->table_exists('mdl_' . $modifier)) {
            return $DB->get_record('mdl_' . $modifier, ['id' => $instanceid]);
        } else {
            return null;
        }
    }
}
