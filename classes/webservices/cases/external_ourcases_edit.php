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
 * WebService to edit cases
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/cases.php');

use local_dta\Cases;

/**
 * This class is used to edit cases
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_ourcases_edit extends external_api
{

    public static function ourcases_edit_parameters()
    {
        return new external_function_parameters(
            array(
                'ourcaseid' => new external_value(PARAM_INT, 'Our Case ID'),
                'status' => new external_value(PARAM_INT, 'Status', VALUE_DEFAULT, 0),
                'tags' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'ID del elemento')  , 'Tags' , VALUE_DEFAULT, []
                )
            )
        );
    }

    public static function ourcases_edit($ourcaseid, $status = 0, $tags = [])
    {
        
        if (!$ourcase = Cases::get_case($ourcaseid, false)) {
            return array('result' => false, 'error' => 'Our case not found');
        }

        if (empty($ourcaseid) || empty($status) ) {
            return array('result' => false, 'error' => 'Empty Values');
        }

        $newcase = new stdClass();
        $newcase->id = $ourcaseid;
        $newcase->status = $status;
        $newcase->tags = $tags;


        if (!Cases::update_case($newcase)) {
            return array('result' => false, 'error' => 'Failed to update our case');
        }

        return array('result' => true);
    }

    public static function ourcases_edit_returns()
    {
        return new external_single_structure(
            array(
                'error' => new external_value(PARAM_TEXT, 'Error message if any' , VALUE_OPTIONAL),
                'result' => new external_value(PARAM_BOOL, 'True if success, false otherwise' , VALUE_OPTIONAL)
            )
        );
    }
}
