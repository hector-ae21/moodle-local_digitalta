<?php

/**
 * Resource class.
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This file controls DTA files as an instance of the repository and digital_file table in the database
// NOT TO BE CONFUSED WITH THE FILEMANAGER HANDLER OR MOODLE FILE API

namespace local_dta;

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
    private static $table = 'digital_resource';

    /** @var string The name of the database table storing the resources. */
    private static $table_tags = 'digital_resource_tags';

    /**
     * Constructor.
     * 
     * @param $experience array The data to populate the experience with.
     */
    public function __construct($experience = null)
    {
        foreach ($experience as $key => $value) {
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
            throw new Exception('Error adding experience: missing fields');
        }

        $record = self::prepare_metadata_record($resource);
    
        if (self::resource_exists($resource)) {
        
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
            return true;
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
        if (!self::check_resource_type($resource->type)) {
            throw new Exception('Invalid resource type');
        }
        $record = new \stdClass();
        $record->name = $resource->name;
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
        return $DB->get_records(self::$table);
    }
}