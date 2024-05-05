<?php

/**
 * external_resources_save
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/resources.php');
require_once($CFG->dirroot . '/local/dta/classes/resource.php');

use local_dta\Resources;
use local_dta\Resource;

class external_resources_upsert extends external_api
{

    public static function resources_upsert_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Resource identifier'),
                'name' => new external_value(PARAM_TEXT, 'Resource name'),
                'description' => new external_value(PARAM_TEXT, 'Resource description'),
                'themes' => new external_multiple_structure(
                    new external_value(PARAM_TEXT, 'Resource themes')
                ),
                'tags' => new external_multiple_structure(
                    new external_value(PARAM_TEXT, 'Resource tags')
                ),
                'type' => new external_value(PARAM_INT, 'Resource type'),
                'format' => new external_value(PARAM_INT, 'Resource format'),
                'path' => new external_value(PARAM_TEXT, 'Resource path'),
                'lang' => new external_value(PARAM_TEXT, 'Resource language'),
            )
        );
    }

    public static function resources_upsert($id, $name, $description, $themes, $tags, $type, $format, $path, $lang)
    {
        global $USER;

        $resource              = new Resource();
        $resource->id          = $id;
        $resource->name        = $name;
        $resource->description = $description;
        $resource->themes      = $themes;
        $resource->tags        = $tags;
        $resource->type        = $type;
        $resource->format      = $format;
        $resource->path        = $path;
        $resource->lang        = $lang;
        $resource->userid      = $USER->id;

        if (!$resource = Resources::upsert_resource($resource)) {
            return [
                'result' => false,
                'error' => 'Error saving resource'
            ];
        }

        return [
            'result' => true,
            'resourceid' => $resource->id
        ];
    }

    public static function resources_upsert_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'resourceid' => new external_value(PARAM_INT, 'Resource ID' , VALUE_OPTIONAL),
                'error' => new external_value(PARAM_RAW, 'Error message' , VALUE_OPTIONAL),
            ]
        );
    }
}
