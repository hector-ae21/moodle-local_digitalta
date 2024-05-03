<?php

/**
 * Resource class.
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This file controls DTA resource as an instance of the repository and digital_resource table in the database
// NOT TO BE CONFUSED WITH THE FILEMANAGER HANDLER OR MOODLE FILE API

namespace local_dta;

require_once(__DIR__ . '/constants.php');
require_once(__DIR__ . '/context.php');

use \local_dta\CONSTANTS;
use \local_dta\Context;

use Exception;

class Resource {
    /** @var int The ID of the resource. */
    private $id;
    
    /** @var string The name of the resource. */
    private $name;
    
    /** @var string The description of the resource. */
    private $description;
    
    /** @var string The type of the resource. */
    private $type;
    
    /** @var string The path of the resource. */
    private $path;
    
    /** @var string The language of the resource. */
    private $lang;
    
    /** @var string The timestamp of when the resource was created. */
    private $timecreated;
    
    /** @var string The timestamp of when the resource was last modified. */
    private $timemodified;
    
    /** @var string The name of the database table storing the resources. */
    private static $table = 'digital_resources';

    /** @var string The name of the database table storing the resources. */
    private static $table_tags = 'digital_resource_tags';

    /**
     * Constructor.
     * 
     * @param $resource mixed The resource to construct.
     */
    public function __construct($resource = null)
    {
        foreach ($resource as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = ($key === 'description' && is_array($value))
                    ? $value['text']
                    : $value;
            }
        }
    }

    /**
     * Magic method to get a property.
     * 
     * @param $name string The name of the property.
     * 
     * @return mixed The value of the property.
     */
    public function __get($name)
    {
        return $this->{$name};
    }

    /**
     * Magic method to set a property.
     * 
     * @param $name string The name of the property.
     * @param $value mixed The value to set.
     * 
     * @return void
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }


    /**
     * Upsert a resource.
     * 
     * @param $resource mixed The resource to upsert.
     * 
     * @return object The upserted resource.
     * 
     * @throws Exception If metadata fields are missing.
     */
    public static function upsert($resource) : object{
        if (self::is_resource_metadata_incomplete($resource)) {
            throw new Exception('Error adding resources: missing fields');
        }

        $record = self::prepare_metadata_record($resource);
    
        if (self::resource_exists($resource)) {
            //TODO update 
        } else {
            // TODO add tags, theme and create file
            // Create file to set path
            $record->id = self::create_resource($record);
        }

        return Resource::get_resource($record->id);

    }

    /**
     * Check if resource metadata is incomplete.
     * 
     * @param $resource object The resource object to check.
     * 
     * @return bool True if metadata is incomplete, false otherwise.
     */
    private static function is_resource_metadata_incomplete(object $resource): bool {
        // TODO add tags, theme and path to the validation if needed if not remove this 😁
        if (!self::check_resource_type($resource->type)) {
            echo $resource->type;
            throw new Exception('Invalid resource type');
        }

        return !isset($resource->name) || !isset($resource->type) || !isset($resource->lang)
            || empty($resource->name) || empty($resource->type) || empty($resource->lang)
            || is_null($resource->name) || is_null($resource->type) || is_null($resource->lang);
    }
    

    /**
     * Check if the resource type is valid.
     * 
     * @param $type string The resource type to check.
     * 
     * @return bool True if the type is valid, false otherwise.
     */
    private static function check_resource_type(string $type) : bool {
        return in_array($type, CONSTANTS::FILE_TYPES);
    }

    /**
     * Prepare metadata record for database insertion.
     * 
     * @param $resource object The resource object.
     * 
     * @return object The prepared metadata record.
     * 
     * @throws Exception If the resource type is invalid.
     */
    private static function prepare_metadata_record(object $resource) : object{
        global $USER;
        if (!self::check_resource_type($resource->type)) {
            throw new Exception('Invalid resource type');
        }
        $record = new \stdClass();
        $record->name = $resource->name;
        $record->userid = $USER->id; 
        $record->description = $resource->description;
        $record->type = $resource->type;
        $record->lang = $resource->lang;
        $record->path = $resource->path;
        $record->timecreated = date('Y-m-d H:i:s', time());
        $record->timemodified = date('Y-m-d H:i:s', time());
        return $record;
    }

    /**
     * Check if a resource exists.
     * 
     * @param $resource object The resource object to check.
     * 
     * @return bool True if the resource exists, false otherwise.
     */
    public static function resource_exists(object $resource) : bool{
        global $DB;
        return $DB->record_exists(self::$table, ['name' => $resource->name, 'type' => $resource->type, 'lang' => $resource->lang]);
    }

    /**
     * Get a resource by its ID.
     * 
     * @param $id int The ID of the resource.
     * 
     * @return object The resource object.
     */
    public static function get_resource(int $id) : object{
        global $DB;
        return $DB->get_record(self::$table, ['id' => $id]);
    } 

    /**
     * Create a resource.
     * 
     * @param $resource object The resource object to create.
     * 
     * @return int The ID of the created resource.
     */
    public static function create_resource(object $resource) : int{
        global $DB;
        return $DB->insert_record(self::$table, $resource);
    }


    /**
     * Get resources by type.
     * 
     * @param $type string The type of the resources.
     * 
     * @return array The resources.
     */
    public static function get_resources_by_type(string $type) : array{
        global $DB;
        return $DB->get_records(self::$table, ['type' => $type]);
    }

    /**
     * Get all resources.
     * 
     * @return array The resources.
     */
    public static function get_all_resources() : array{
        global $DB;
        return array_values($DB->get_records(self::$table));
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
    public static function get_resources_by_context_component(string $component, int $componentinstance) : array{
        $context = Context::get_contexts_by_component($component, $componentinstance, 'resource');

        $resources = array();
        foreach ($context as $unique_context) {
            $resources[] = self::populate_context($unique_context);
        }
        return array_values($resources);
    }
}



class ResourceRepository {

    /**
     * Get resources based on provided filters.
     *
     * @param array $filters Array of filters to apply.
     *                       Accepted filters: 'type', 'language'.
     * @return array Array of filtered resources.
     */
    public static function get_resources(array $filters = []) : array {
        $resources = array();

        if (empty($filters)) {
            return self::get_all_resources();
        }

        foreach ($filters as $filter_key => $filter_value) {
            switch ($filter_key) {
                case 'type':
                    $resources = self::apply_type_filter($filter_value, $resources);
                    break;
                case 'language':
                    $resources = self::apply_language_filter($filter_value, $resources);
                    break;
                default:
                    break;
            }
        }

        return $resources;
    }

    /**
     * Apply type filter to the resources.
     *
     * @param array $types Array of resource types to filter by.
     * @param array $resources Array of resources to filter.
     * @return array Array of filtered resources.
     */
    private static function apply_type_filter(array $types, array $resources) : array {
        $filtered_resources = array();

        foreach ($types as $type) {

            $type_resources = Resource::get_resources_by_type($type);
            if (empty($filtered_resources)) {
                $filtered_resources = $type_resources;
            } else {
                $filtered_resources = array_intersect($filtered_resources, $type_resources);
            }
        }
        return $filtered_resources;
    }

    /**
     * Apply language filter to the resources.
     *
     * @param array $languages Array of languages to filter by.
     * @param array $resources Array of resources to filter.
     * @return array Array of filtered resources.
     */
    private static function apply_language_filter(array $languages, array $resources) : array {
        $filtered_resources = array();
        foreach ($languages as $language) {
            foreach ($resources as $resource) {
                if ($resource->lang == $language) {
                    $filtered_resources[] = $resource;
                }
            }
        }
        return $filtered_resources;
    }

    /**
     * Get all resources.
     *
     * @return array Array of all resources.
     */
    private static function get_all_resources() : array {
        return Resource::get_all_resources();
    }
}
