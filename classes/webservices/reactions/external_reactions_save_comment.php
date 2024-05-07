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
 * WebService to save a comment
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This class is used to save a comment
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_reactions_save_comment extends external_api
{

    public static function reactions_save_comment_parameters()
    {
        return new external_function_parameters(
            array(
                'instancetype' => new external_value(PARAM_INT, 'Type of instance 1 for experiences, 0 for cases', VALUE_REQUIRED),
                'instanceid' => new external_value(PARAM_INT, 'ID of the instance', VALUE_REQUIRED),
                'comment' => new external_value(PARAM_RAW, 'Comment', VALUE_REQUIRED)
            )
        );
    }

    public static function reactions_save_comment($instancetype, $instanceid, $comment)
    {
        global $USER, $DB;

        if (!self::validate_instance_id($instancetype, $instanceid)) {
            return array('result' => false, 'error' => 'Invalid instance id');
        }

        $table = self::get_table($instancetype);
        $column = self::get_column($instancetype);

        $record = new \stdClass();
        $record->userid = $USER->id;
        $record->$column = $instanceid;
        $record->comment = $comment;

        $DB->insert_record($table, $record);

        return ['result' => true, 'comment' => $comment, 'user' => ['id' => $USER->id, 'fullname' => $USER->fullname]];
    }

    protected static function validate_instance_id($type, $instanceid)
    {
        global $DB;
        switch ($type) {
            case 0:
                return $DB->get_record('digital_cases', array('id' => $instanceid));
            case 1:
                return $DB->get_record('digital_experiences', array('id' => $instanceid));
            default:
                return false;
        }
    }

    protected static function get_table($type)
    {
        switch ($type) {
            case 0:
                return 'digital_cases_comments';
            case 1:
                return 'digital_experiences_comments';
            default:
                return false;
        }
    }

    protected static function get_column($type)
    {
        switch ($type) {
            case 0:
                return 'caseid';
            case 1:
                return 'experienceid';
            default:
                return false;
        }
    }

    public static function reactions_save_comment_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result of the operation'),
                'comment' => new external_value(PARAM_RAW, 'Comment'),
                'user' => new external_single_structure(
                    [
                        'id' => new external_value(PARAM_INT, 'User ID'),
                        'fullname' => new external_value(PARAM_RAW, 'User Fullname')
                    ]
                )
            ]
        );
    }
}
