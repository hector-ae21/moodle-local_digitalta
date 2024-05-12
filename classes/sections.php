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
 * Sections class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

use Exception;
use stdClass;

/**
 * This class is used to manage the sections of the plugin
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Sections
{
    /** @var string The table name for the sections. */
    private static $table = 'digital_sections';

    /** @var string The table name for the groups. */
    private static $groups_table = 'digital_sections_groups';

    /** @var string The table name for the types. */
    private static $types_table = 'digital_sections_types';

    /**
     * Get a section by id
     *
     * @param  int    $id The id of the section.
     * @return object The section
     */
    public static function get_section(int $id)
    {
        global $DB;
        return $DB->get_record(self::$table, ['id' => $id]);
    }

    /**
     * Get all the sections
     *
     * @return array The sections
     */
    public static function get_all_sections()
    {
        global $DB;
        return $DB->get_records(self::$table, null, 'component, componentinstance, groupid, sequence');
    }

    /**
     * Get sections based on provided filters.
     *
     * @param  array $filters Array of filters to apply. Accepted: component, componentinstance, groupname, groupid, sequence.
     * @return array Array of filtered sections.
     */
    public static function get_sections(array $filters = []) : array {
        $sections = self::get_all_sections();

        if (empty($filters)) {
            return $sections;
        }

        $allowed_filters = ['component', 'componentinstance', 'groupid', 'groupname', 'sequence'];

        foreach ($filters as $filter_key => $filter_value) {
            if (!in_array($filter_key, $allowed_filters)) {
                continue;
            }
            $sections = self::apply_filter($filter_key, $filter_value, $sections);
        }

        return $sections;
    }

    /**
     * Apply a filter to the sections.
     *
     * @param  string $filter_key The key of the filter.
     * @param  array  $filter_value The value of the filter.
     * @param  array  $sections The sections to filter.
     * @return array  The filtered sections.
     */
    private static function apply_filter(string $filter_key, array $filter_value, array $sections) : array {
        global $DB;
        $filtered_sections = [];
        foreach ($filter_value as $value) {
            switch ($filter_key) {
                case 'groupname':
                    $filter_key = 'groupid';
                    if (!$value = self::get_group_by_name($value)->id) {
                        return $filtered_sections;
                    }
                    break;
                default:
                    break;
            }
            foreach ($sections as $section) {
                if ($section->{$filter_key} == $value) {
                    $filtered_sections[] = $section;
                }
            }
        }
        return $filtered_sections;
    }

    /**
     * Upsert a section
     * 
     * @param  object      $section The section to upsert.
     * @return object|null The section
     */
    public static function upsert_section(object $section) : object
    {
        global $DB;
        $record = new stdClass();
        $record = self::prepare_metadata_record($section);
        if (property_exists($section, 'id')
                and !empty($section->id)
                and $section->id > 0) {
            if (!$current_section = self::get_section($section->id)) {
                return null;
            }
            $record->id          = $section->id;
            $record->timecreated = $current_section->timecreated;
            $DB->update_record(self::$table, $section);
        } else {
            $record->id = $DB->insert_record(self::$table, $record);
        }
        return $section;
    }

    /**
    * Prepare metadata record for database insertion.
    * 
    * @param  object    $section The section object.
    * @return object    The prepared metadata record.
    * @throws Exception If the section type is invalid.
    */
    private static function prepare_metadata_record(object $section) : object {
        self::validate_metadata($section);
        $record                    = new stdClass();
        $record->component         = $section->component;
        $record->componentinstance = $section->componentinstance;
        $record->groupid           = $section->groupid;
        $record->sequence          = property_exists($section, 'sequence')
            ? $section->sequence
            : self::get_next_available_sequence_for_component(
                $section->component,
                $section->componentinstance,
                $section->groupid);
        $record->sectiontype       = $section->sectiontype;
        $record->content           = $section->content;
        $record->timecreated       = time();
        $record->timemodified      = time();
        return $record;
    }

    /**
     * Validate the metadata of a section.
     * 
     * @param  object $section The section object to check.
     */
    private static function validate_metadata(object $section) {
        $keys = ['component', 'componentinstance', 'groupid', 'sectiontype'];
        $missing_keys = [];
        foreach ($keys as $key) {
            if (!property_exists($section, $key) || empty($section->{$key}) || is_null($section->{$key})) {
                $missing_keys[] = $key;
            }
        }
        if (!empty($missing_keys)) {
            throw new Exception('Error adding section. Missing fields: ' . implode(', ', $missing_keys));
        }
    }

    /**
     * Delete a section by id.
     * 
     * @param  int  $id The id of the section.
     * @return bool True if the section was deleted, false otherwise.
     */
    public static function delete_section(int $id) : bool {
        global $DB;
        return $DB->delete_records(self::$table, ['id' => $id]);
    }

    /**
     * Get all the section types.
     * 
     * @return array The section types.
     */
    public static function get_types() : array {
        global $DB;
        if (!$types = $DB->get_records(self::$types_table)) {
            return [];
        }
        return $types;
    }

    /**
     * Get a section type by its identifier.
     * 
     * @param  int         $id The identifier of the type.
     * @return object|null The type object.
     */
    public static function get_type(int $id) {
        global $DB;
        if (!$type = $DB->get_record(self::$types_table, ['id' => $id])) {
            return null;
        }
        return $type;
    }

    /**
     * Get a section type by its name.
     * 
     * @param  string      $name The name of the type.
     * @return object|null The type object.
     */
    public static function get_type_by_name(string $name) {
        global $DB;
        if (!$type = $DB->get_record(self::$types_table, ['name' => $name])) {
            return null;
        }
        return $type;
    }

    /**
     * Get all the section groups.
     *
     * @return array The section groups.
     */
    public static function get_groups() : array {
        global $DB;
        if (!$groups = $DB->get_records(self::$groups_table)) {
            return [];
        }
        return $groups;
    }

    /**
     * Get a section group by its identifier.
     *
     * @param  int         $id The identifier of the group.
     * @return object|null The group object.
     */
    public static function get_group(int $id) {
        global $DB;
        if (!$group = $DB->get_record(self::$groups_table, ['id' => $id])) {
            return null;
        }
        return $group;
    }

    /**
     * Get a section group by its name.
     *
     * @param  string      $name The name of the group.
     * @return object|null The group object.
     */
    public static function get_group_by_name(string $name) {
        global $DB;
        if (!$group = $DB->get_record(self::$groups_table, ['name' => $name])) {
            return null;
        }
        return $group;
    }

    /**
     * Get the next available sequence for a component.
     * 
     * @param  int $component The component id.
     * @param  int $componentinstance The component instance id.
     * @param  int $groupid The group id.
     * @return int The next available sequence.
     */
    public static function get_next_available_sequence_for_component(int $component, int $componentinstance, int $groupid) : int {
        global $DB;
        if (!$sequence = $DB->get_field(self::$table, 'MAX(sequence)', [
                'component' => $component,
                'componentinstance' => $componentinstance,
                'groupid' => $groupid])) {
            return 1;
        }
        return $sequence + 1;
    }
}
