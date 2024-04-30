<?php

/**
 * external_resources_save
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../../resource.php');


use local_dta\Resource;

class external_resources_upsert extends external_api
{

    public static function resources_upsert_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Resource ID'),
                'name' => new external_value(PARAM_TEXT, 'Resource name'),
                'description' => new external_value(PARAM_TEXT, 'Resource description'),
                'type' => new external_value(PARAM_TEXT, 'Resource type'),
                'path' => new external_value(PARAM_TEXT, 'Resource path'),
                'lang' => new external_value(PARAM_TEXT, 'Resource language'),
            )
        );
    }

    public static function resources_upsert($id, $name, $description, $type, $path, $lang)
    {
        global $USER;
        $resource = new stdClass();
        $resource->id = $id;
        $resource->name = $name;
        $resource->description = $description;
        $resource->type = $type;
        $resource->path = $path;
        $resource->lang = $lang;
        $resource->userid = $USER->id;


        if(!$resource = Resource::upsert($resource)){
            return [
                'result' => false,
                'error' => 'Error saving resourcec'
            ];
        }

        return [
            'result' => true,
            'experienceid' => $resource->id,
        ];
    }

    public static function resources_upsert_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'resource' => new external_value(PARAM_INT, 'Section ID' , VALUE_OPTIONAL),
                'error' => new external_value(PARAM_RAW, 'Error message' , VALUE_OPTIONAL),
            ]
        );
    }
}
