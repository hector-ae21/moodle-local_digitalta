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
 * Resource class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

/**
 * This class is used to represent the resource entity.
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Resource
{
    /** @var int The ID of the resource. */
    public $id;
    
    /** @var string The name of the resource. */
    public $name;
    
    /** @var string The description of the resource. */
    public $description;
    
    /** @var string The type of the resource. */
    public $type;

    /** @var string The format of the resource. */
    public $format;
    
    /** @var string The path of the resource. */
    public $path;

    /** @var string The language of the resource. */
    public $lang;

    /** @var int The ID of the user who created the resource. */
    public $userid;
    
    /** @var string The timestamp of when the resource was created. */
    public $timecreated;
    
    /** @var string The timestamp of when the resource was last modified. */
    public $timemodified;

    /** @var string The themes of the resource. */
    public $themes;

    /** @var string The tags of the resource. */
    public $tags;

    /**
     * Constructor.
     * 
     * @param $resource mixed The resource to construct.
     */
    public function __construct($resource = [])
    {
        foreach ($resource as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = ($key === 'description' && is_array($value))
                    ? $value['text']
                    : $value;
            }
        }
    }
}
