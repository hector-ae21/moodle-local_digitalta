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
 * WebService to get mentors
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/tags.php');
/**
 * This class is used to create tags
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_get_mentors extends external_api
{

    public static function get_mentors_parameters()
    {
        return new external_function_parameters(
            [
                'searchText' => new external_value(PARAM_TEXT, 'Search text', VALUE_DEFAULT, '%%')
            ]
        );
    }

    public static function get_mentors($searchText = '%%')
    {
        global $DB;
        $searchText = '%' . $searchText . '%';
        $likementor = $DB->sql_like('name', ':name');
        $mentors = $DB->get_records_sql("SELECT * FROM {user} WHERE {$likementor}",
            ['name' => '%' . $searchText . '%']);
        
        $mentors = array_map(function ($mentor) {
            return [
                'id' => $mentor->id,
                'firstname' => $mentor->firstname,
                'lastname' => $mentor->lastname,
            ];
        }, $mentors);

        return $mentors;
    }

    public static function get_mentors_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Mentor id'),
                    'firstname' => new external_value(PARAM_TEXT, 'Mentor firstname'),
                    'lastname' => new external_value(PARAM_TEXT, 'Mentor lastname'),
                )
            )
        );
    }
}