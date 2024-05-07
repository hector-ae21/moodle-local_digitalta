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
 * WebService to create tags
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/tags.php');

use local_dta\Tags;

/**
 * This class is used to create tags
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_create_tags extends external_api
{

    public static function create_tags_parameters()
    {
        return new external_function_parameters(
            [
                'tag' => new external_value(PARAM_TEXT, 'Tag name', VALUE_REQUIRED)
            ]
        );
    }

    public static function create_tags($tag)
    {
        $tagid = Tags::add_tag($tag);
        return ['id' => $tagid];
    }

    public static function create_tags_returns()
    {
        return new external_single_structure(
            [
                'id' => new external_value(PARAM_INT, 'Tag id')
            ]
        );
    }
}
