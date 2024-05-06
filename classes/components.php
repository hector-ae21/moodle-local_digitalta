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
 * Components class
 *
 * @package   local_dta
 * @copyright 2024 Salvador Banderas Rovira
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

require_once($CFG->dirroot . '/local/dta/locallib.php');

use stdClass;
use Exception;

defined('MOODLE_INTERNAL') || die();

/**
 * This class is used to manage the components of the plugin
 *
 * @copyright 2024 Salvador Banderas Rovira
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Components
{
    /** @var string The table name for the components. */
    private static $table = 'digital_components';

    /**
     * Get all the components
     *
     * @return array
     */
    public static function get_all_components()
    {
        global $DB;
        return $DB->get_records(self::$table);
    }

    /**
     * Get the component by id
     *
     * @param  int         $id The component identifier
     * @return object|null The component
     */
    public static function get_component_by_id(int $id)
    {
        global $DB;
        return $DB->get_record(self::$table, ['id' => $id]);
    }

    /**
     * Get the component by name
     *
     * @param  string      $name The component name
     * @return object|null The component
     */
    public static function get_component_by_name(string $name)
    {
        global $DB;
        return $DB->get_record(self::$table, ['name' => $name]);
    }
}
