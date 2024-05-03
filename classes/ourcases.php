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
 * OurCases class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

require_once(__DIR__ . '/reactions.php');
require_once(__DIR__ . '/experience.php');
require_once(__DIR__ . '/tags.php');
require_once(__DIR__ . '/context.php');
require_once(__DIR__ . '/utils/date_utils.php');


use stdClass;
use Exception;
use local_dta\Reaction;
use local_dta\Tags;
use local_dta\Experience;
use local_dta\utils\date_utils;
use local_dta\Context;

/**
 * This class is used to manage the cases of the plugin
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class OurCases
{
    private static $table = 'digital_ourcases';
    private static $table_section_text = 'digital_oc_sec_text';
    private $db;
    public $id;
    private $experienceid;
    private $userid;
    private $timecreated;
    private $timemodified;
    private $status;

    /**
     * OurCases constructor
     */
    public function __construct($ourcase = null)
    {
        global $DB;
        $this->db = $DB;
        if ($ourcase && is_object($ourcase)) {
            $this->id = $ourcase->id;
            $this->experienceid = $ourcase->experienceid;
            $this->userid = $ourcase->userid;
            $this->timecreated = $ourcase->timecreated;
            $this->timemodified = $ourcase->timemodified;
            $this->status = $ourcase->status;
        }
    }
    /**
     * Get all cases
     * 
     * @param bool $with_extra_fields Indicates whether to get extra fields
     * @return array Returns an array of records
     */
    public static function get_cases($with_extra_fields = true, $active = 1)
    {
        global $DB;
        $cases = $DB->get_records(self::$table , ['status' => $active]);
        if ($with_extra_fields) {
            $cases = self::get_extra_fields($cases);
        }
        return $cases;
    }

    /**
     * Get all cases by user
     * 
     * @param int $userid ID of the user
     * @return array Returns an array of records
     */

    public static function get_cases_by_user($userid)
    {
        global $DB;
        $cases = $DB->get_records(self::$table, ['userid' => $userid]);
        $cases = self::get_extra_fields($cases);
        return $cases;
    }

    /**
     * Get active cases 
     *
     * @return array Returns an array of records
     */
    public static function get_active_cases()
    {
        global $DB;
        $cases = $DB->get_records(self::$table, ['status' => 1], 'timecreated DESC');
        $cases = self::get_extra_fields($cases);
        return $cases;
    }
    /**
     * Get a specific case
     *
     * @param int $id ID of the case
     * @return object Returns a record object
     */
    public static function get_case($id, $fullInformation = true)
    {
        global $DB;
        // add extra fields
        $case = $DB->get_record(self::$table, ['id' => $id]);
        if ($case && $fullInformation) {
            $case = self::get_extra_fields([$case])[0];
        }
        return $case;
    }

    /**
     * Get all cases by experience
     *
     * @param int $id ID of the case
     * @return object Returns a record object
     */
    public static function get_cases_by_experience($experience)
    {
        global $DB;
        return $DB->get_records(self::$table, ['experienceid' => $experience], 'timecreated DESC');
    }
    /**
     * Add a case
     *
     * @param int $experienceid ID of the experience
     * @param bool $status Status of the case
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function add_with_experience($experienceid, $userid, $status = 0)
    {
        global $DB;
        if (empty($experienceid) ||empty($userid)) {
            return false;
        }

        // verify if the experience exists
        if (!$experience = Experience::get_experience($experienceid)) {
            return false;
        }

        $record = new stdClass();
        $record->experienceid = $experienceid;
        $record->userid = $userid;
        $record->timecreated = date('Y-m-d H:i:s', time());
        $record->timemodified = date('Y-m-d H:i:s', time());
        $record->status = $status;

        if (!$id = $DB->insert_record(self::$table,  $record)) {
            throw new Exception('Error adding ourcase to the database.');
        }

        $record->id = $id;

        // adding default section text
        $record_section = new stdClass();
        $record_section->ourcaseid = $id;
        $record_section->title = $experience->title;
        $record_section->description = $experience->description;
        $record_section->sequence = 0;

        if (!$DB->insert_record(self::$table_section_text,  $record_section)) {
            throw new Exception('Error adding ourcase section text to the database.');
        }


        return new OurCases($record);
    }


    /**
     * Add a case
     *
     * @param string $timecreated Date of the case
     * @param bool $status Status of the case
     * @return object|bool Returns a record object 
     */
    public static function add_without_experience($timecreated, $userid, $status = 0)
    {
        global $DB;
        if (empty($timecreated) || empty($userid)) {
            return false;
        }

        if($existing = $DB->get_record(self::$table, ['userid' => $userid, 'status' => $status , 'experienceid' => 0])){
            return $existing;
        }

        
        $record = new stdClass();
        $record->experienceid = 0;
        $record->userid = $userid;
        $record->timecreated = $timecreated;
        $record->timemodified = $timecreated;
        $record->status = $status;

        if (!$id = $DB->insert_record(self::$table,  $record)) {
            throw new Exception('Error adding ourcase to the database.');
        }

        $record->id = $id;

        // adding default section text
        $record_section = new stdClass();
        $record_section->ourcaseid = $id;
        $record_section->title = "";
        $record_section->description = "";
        $record_section->sequence = 0;

        if (!$DB->insert_record(self::$table_section_text,  $record_section)) {
            throw new Exception('Error adding ourcase section text to the database.');
        }

        return new OurCases($record);
    }

    /**
     * Update a case
     *
     * @param object $ourcase Case object
     * @return bool Returns true if successful, false otherwise
     */
    public static function update_case($ourcase)
    {
        global $DB;
        $ourcase->timemodified = date('Y-m-d H:i:s', time());
        if (!$DB->update_record(self::$table,  $ourcase)) {
            throw new Exception('Error adding ourcase section text to the database.');
            return false;
        }
        if($ourcase->tags) {
            Tags::update_tags('case', $ourcase->id, $ourcase->tags);
        }
        return true;    



    }

    /**
     * Delete a case by ID and all its sections
     *
     * @param int $id ID of the case
     * @return bool Returns true if successful, false otherwise
     */
    public static function delete_case($id)
    {
        global $DB;
        if (empty($id)) {
            return false;
        }
        // delete all sections
        if (!$DB->delete_records(self::$table_section_text, ['ourcaseid' => $id])) {
            return false;
        }        
        return $DB->delete_records(self::$table, ['id' => $id]);
    }

    /**
     * Get the text of a section by section id order by sequence ignoring sequence 0
     *
     * @param int $ourcase ID of the section
     * @param bool $get_header Indicates whether to get the header section
     * @return array Returns an array of record objects
     */
    public static function get_sections_text($ourcase, $get_header = false)
    {
        global $DB;

        $sql = "SELECT * FROM {" . self::$table_section_text . "} WHERE ourcaseid = ? ";
        if (!$get_header) {
            $sql .= "AND sequence <> 0";
        }
        $sql .= " ORDER BY sequence";

        $params = [$ourcase];
        return $DB->get_records_sql($sql, $params);
    }


    /**
     * Get the text of a section by section id order by sequence ignoring sequence 0
     *
     * @param int $ourcase ID of the section
     * @return object Returns a record object
     */
    public static function get_section_header($ourcase)
    {
        global $DB;
        return $DB->get_record(self::$table_section_text, ['ourcaseid' => $ourcase, 'sequence' => 0]);
    }


    /**
     * Get all kind of sections
     *
     * @param int $id ID of the case
     * @return object Returns a record object
     */
    public static function get_sections($id)
    {
        global $DB;
        return $DB->get_records(self::$table_section_text, ['ourcase' => $id]);
    }

    /**
     * Get a specific section
     *
     * @param int $id ID of the section
     * @param int $sequence Sequence of the section
     * @return object Returns a record object
     */
    public static function get_section_by_sequence($id, $sequence)
    {
        global $DB;
        return $DB->get_record(self::$table_section_text, ['ourcase' => $id, 'sequence' => $sequence]);
    }

    /**
     * Get extra fields for cases
     * 
     * @param array $cases
     * @return array
     */
    public static function get_extra_fields($cases)
    {
        global $PAGE;
        foreach ($cases as $case) {
            $user = get_complete_user_data("id", $case->userid);
            $picture = new \user_picture($user);
            $picture->size = 101;
            $case->timecreated = date_utils::time_elapsed_string($case->timecreated);
            $case->user = [
                'id' => $user->id,
                'name' => $user->firstname . " " . $user->lastname,
                'email' => $user->email,
                'imageurl' => $picture->get_url($PAGE)->__toString(),
                'profileurl' => new \moodle_url('/user/profile.php', ['id' => $user->id])
            ];
            $case->pictureurl = self::get_picture_url($case);
            $case->reactions = Reaction::get_reactions_for_render_case($case->id);
            $tags = Tags::get_tags_for_component('case', $case->id);
            $transformedTags = array_map(function($tag) {
                return (object)[
                    'name' => $tag->name,
                    'id' => $tag->id
                ];
            }, array_values($tags));
            $case->tags = $transformedTags;
        }
        return $cases;
    }

    /**
     * Gets the picture url for the case.
     */
    public static function get_picture_url($case) {
        global $CFG;

        $fs = get_file_storage();
        $files = $fs->get_area_files(
            \context_system::instance()->id,
            'local_dta',
            'picture',
            $case->id,
            'sortorder DESC, id ASC',
            false
        );

        if (empty($files)) {
            return false;
        }

        $file = reset($files);
        $pictureurl = \moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );
        return $pictureurl;
    }

        /**
     * Get resources by IDs.
     * @param array $ids The IDs of the resources.
     * @return array The resources.
     */
    public static function get_cases_by_ids(array $ids) : array {
        global $DB;
        $resources = array();
    
        if (!empty($ids)) {
            $where_clause = "WHERE ";
            foreach ($ids as $id) {
                $where_clause .= "id = " . (int)$id . " OR ";
            }
            $where_clause = rtrim($where_clause, " OR ");
            $sql = "SELECT * FROM {" . self::$table . "} " . $where_clause;    
            $resources = $DB->get_records_sql($sql);
        }

        // get section header and add 
        foreach ($resources as $resource) {
            $resource->section_header = self::get_section_header($resource->id);
        }
        
        return $resources;
    }

    
    /**
     * Populate the context of a resource.
     * 
     * @param $unique_context object The unique context.
     * 
     * @return object The resource with the populated context.
     */
    public static function populate_context(object $unique_context): object {
        $resource = self::get_case($unique_context->modifierinstance);
        $resource->context = $unique_context;
        $resource->section_header = self::get_section_header($resource->id);
        return $resource;
    }

    
    /**
     * Get resources by context and component.
     * @param string $component The component.
     * @param int $componentinstance The component instance.
     * @return array The resources.
     */
    public static function get_cases_by_context_component(string $component, int $componentinstance) : array{
        $context = Context::get_contexts_by_component($component, $componentinstance, 'resource');

        if(!$context) {
            return [];
        }
        $resources = array();
        foreach ($context as $unique_context) {
            $resources[] = self::populate_context($unique_context);
        }
        return array_values($resources);
    }
}
