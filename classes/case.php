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
 * StudyCase class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

/**
 * This class is used to represent the case entity.
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class StudyCase
{
    /** @var int The ID of the case. */
    public $id;

    /** @var int The ID of the experience. */
    public $experienceid;

    /** @var string The ID of the resource. */
    public $resourceid;

    /** @var int The ID of the user. */
    public $userid;

    /** @var string The title of the case. */
    public $title;

    /** @var string The description of the case. */
    public $description;

    /** @var string The language of the case. */
    public $lang;

    /** @var int The status of the case. */
    public $status;

    /** @var string The date the case was created. */
    public $timecreated;

    /** @var string The date the case was modified. */
    public $timemodified;

    /**
     * Constructor.
     * 
     * @param $case mixed The case to construct.
     */
    public function __construct($case = [])
    {
        foreach ($case as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}
