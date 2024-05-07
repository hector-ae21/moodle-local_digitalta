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
 * WebService to upsert a resource
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/resource.php');
require_once($CFG->dirroot . '/local/dta/classes/resources.php');

use local_dta\Resource;
use local_dta\Resources;

/**
 * This class is used to upsert a resource
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
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
