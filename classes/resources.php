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
 * Resources class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

require_once($CFG->dirroot . '/local/dta/classes/resource.php');
require_once($CFG->dirroot . '/local/dta/classes/themes.php');
require_once($CFG->dirroot . '/local/dta/classes/tags.php');

use local_dta\Resource;
use local_dta\Themes;
use local_dta\Tags;

use Exception;

/**
 * This class is used to manage the resources.
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Resources
{
    /** @var string The name of the database table storing the resources. */
    private static $table = 'digital_resources';

    /** @var string The name of the database table storing the types. */
    private static $types_table = 'digital_resources_types';

    /** @var string The name of the database table storing the formats. */
    private static $formats_table = 'digital_resources_formats';

    /**
     * Get a resource by its ID.
     * 
     * @param  int    $id int The ID of the resource.
     * @return object The resource object.
     */
    public static function get_resource(int $id) : object {
        global $DB;
        if (!$resource = $DB->get_record(self::$table, ['id' => $id])) {
            return null;
        }
        $resource         = new Resource($resource);
        $resource->themes = Themes::get_themes_for_component('resource', $resource->id);
        $resource->tags   = Tags::get_tags_for_component('resource', $resource->id);
        return $resource;
    }

    /**
     * Get all resources.
     * 
     * @return array The resources.
     */
    public static function get_all_resources() : array {
        global $DB;
        if (!$resources = $DB->get_records(self::$table)) {
            return [];
        }
        $resources = array_values(array_map(function ($resource) {
            $resource         = new Resource($resource);
            $resource->themes = Themes::get_themes_for_component('resource', $resource->id);
            $resource->tags   = Tags::get_tags_for_component('resource', $resource->id);
            return $resource;
        }, $resources));
        return $resources;
    }

    /**
     * Get resources based on provided filters.
     *
     * @param  array $filters Array of filters to apply. Accepted: type, format, lang.
     * @return array Array of filtered resources.
     */
    public static function get_resources(array $filters = []) : array {
        $resources = self::get_all_resources();
        if (empty($filters)) {
            return $resources;
        }
        $allowed_filters = ['type', 'format', 'lang'];
        foreach ($filters as $filter_key => $filter_value) {
            if (!in_array($filter_key, $allowed_filters)) {
                continue;
            }
            $resources = self::apply_filter($filter_key, $filter_value, $resources);
        }
        return $resources;
    }

    /**
     * Apply a filter to the resources.
     *
     * @param  string $filter_key The key of the filter.
     * @param  array  $filter_value The value of the filter.
     * @param  array  $resources The resources to filter.
     * @return array  The filtered resources.
     */
    private static function apply_filter(string $filter_key, array $filter_value, array $resources) : array {
        $filtered_resources = [];
        foreach ($filter_value as $value) {
            foreach ($resources as $resource) {
                if ($resource->{$filter_key} == $value) {
                    $filtered_resources[] = $resource;
                }
            }
        }
        return $filtered_resources;
    }

    /**
     * Upsert a resource.
     * 
     * @param  object    $resource The resource to upsert.
     * @return object    The upserted resource.
     */
    public static function upsert_resource($resource) : object {
        global $DB;
        $record = self::prepare_metadata_record($resource);
        if (property_exists($resource, 'id')
                and !empty($resource->id)
                and $resource->id > 0) {
            if (!$current_resource = self::get_resource($resource->id)) {
                return null;
            }
            $record->id          = $current_resource->id;
            $record->timecreated = $current_resource->timecreated;
            $DB->update_record(self::$table, $record);
        } else {
            $record->id = $DB->insert_record(self::$table, $record);
        }
        if (!empty($resource->themes)) {
            Themes::update_themes('resource', $record->id, $resource->themes);
        }
        if (!empty($resource->tags)) {
            Tags::update_tags('resource', $record->id, $resource->tags);
        }
        return self::get_resource($record->id);
    }

    /**
    * Prepare metadata record for database insertion.
    * 
    * @param  object    $resource The resource object.
    * @return object    The prepared metadata record.
    * @throws Exception If the resource type is invalid.
    */
    private static function prepare_metadata_record(object $resource) : object {
        global $USER;
        self::validate_metadata($resource);
        $record               = new Resource();
        $record->name         = $resource->name;
        $record->userid       = $USER->id; 
        $record->description  = $resource->description;
        $record->type         = $resource->type;
        $record->format       = $resource->format;
        $record->lang         = $resource->lang;
        $record->path         = $resource->path;
        $record->timecreated  = date('Y-m-d H:i:s', time());
        $record->timemodified = date('Y-m-d H:i:s', time());
        return $record;
    }

    /**
     * Validate the metadata of a resource.
     * 
     * @param  object $resource The resource object to check.
     */
    private static function validate_metadata(object $resource) {
        $keys = ['name', 'type', 'format', 'lang'];
        $missing_keys = [];
        foreach ($keys as $key) {
            if (!property_exists($resource, $key) || empty($resource->{$key}) || is_null($resource->{$key})) {
                $missing_keys[] = $key;
            }
        }
        if (!empty($missing_keys)) {
            throw new Exception('Error adding resource. Missing fields: ' . implode(', ', $missing_keys));
        }
    }

    /**
     * Delete a resource.
     * 
     * @param  int $id The ID of the resource to delete.
     * @return bool True if the resource was deleted, false otherwise.
     */
    public static function delete_resource(int $id) : bool {
        global $DB;
        return $DB->delete_records(self::$table, ['id' => $id]);
    }

    /**
     * Get all the resource types.
     * 
     * @return array The resource types.
     */
    public static function get_types() : array {
        global $DB;
        if (!$types = $DB->get_records(self::$types_table)) {
            return [];
        }
        return $types;
    }

    /**
     * Get a resource type by its identifier.
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
     * Get a resource type by its name.
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
     * Get all the resource formats.
     * 
     * @return array The resource formats.
     */
    public static function get_formats() : array {
        global $DB;
        if (!$formats = $DB->get_records(self::$formats_table)) {
            return [];
        }
        return $formats;
    }

    /**
     * Get a resource format by its identifier.
     * 
     * @param  int         $id The identifier of the format.
     * @return object|null The format object.
     */
    public static function get_format(int $id) {
        global $DB;
        if (!$format = $DB->get_record(self::$formats_table, ['id' => $id])) {
            return null;
        }
        return $format;
    }

    /**
     * Get a resource format by its name.
     * 
     * @param  string      $name The name of the format.
     * @return object|null The format object.
     */
    public static function get_format_by_name(string $name) {
        global $DB;
        if (!$format = $DB->get_record(self::$formats_table, ['name' => $name])) {
            return null;
        }
        return $format;
    }








    /**
     * Populate the context of a resource.
     * 
     * @param $unique_context object The unique context.
     * 
     * @return object The resource with the populated context.
     */
    public static function populate_context(object $unique_context): object {
        $resource = self::get_resource($unique_context->modifierinstance);
        $resource->context = $unique_context;
        return $resource;
    }

    /**
     * Get resources by context and component.
     * @param string $component The component.
     * @param int $componentinstance The component instance.
     * @return array The resources.
     */
    public static function get_resources_by_context_component(string $component, int $componentinstance) : array {
        $context = Context::get_contexts_by_component($component, $componentinstance, 'resource');
 
        $resources = [];
        foreach ($context as $unique_context) {
            $resources[] = self::populate_context($unique_context);
        }
        return array_values($resources);
    }
}
