<?php

/**
 * Experience class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

require_once(__DIR__ . '/../../../config.php'); 
require_once($CFG->dirroot . '/local/dta/classes/experience.php');
require_once($CFG->dirroot . '/local/dta/classes/constants.php');

use stdClass;
use \local_dta\CONSTANTS;

class Reflection extends Experience
{

    private static $table = 'digital_reflection';

    private static $table_section = 'digital_refl_sections';


    /**
     * Get reflections by experience id
     * @param int $experienceid
     * @param int $experienceid
     * @return array
     */
    public static function create_reflection($userid, $experienceid)
    {
        global $DB;

        $reflection = new stdClass();
        $reflection->experienceid = $experienceid;
        $reflection->userid = $userid;
        $reflection->timecreated = date('Y-m-d H:i:s', time());
        $reflection->timemodified = date('Y-m-d H:i:s', time());
        $reflection->status = 0;
        $reflection->visible = 0;

        if ($reflection->id = $DB->insert_record(self::$table, $reflection)) {
            return $reflection;
        };
        return false;
    }


    /**
     * Get reflections by experience id
     *
     * @param object $data
     * @return object | bool
     */
    private static function create_reflection_section($data)
    {
        global $DB;

        if (!isset($data->reflectionid) || !isset($data->sectiontype) || !isset($data->contentid) || !isset($data->groupid))
            throw new \Exception('Invalid data');

        $section = new stdClass();
        $section->reflectionid = $data->reflectionid;
        $section->groupid = $data->groupid;
        $section->sequence = self::get_last_sequence($data->reflectionid) + 1;
        $section->sectiontype = $data->sectiontype;
        $section->contentid = $data->contentid;
        $section->timecreated = date('Y-m-d H:i:s', time());
        $section->timemodified = date('Y-m-d H:i:s', time());

        if ($section->id = $DB->insert_record(self::$table_section, $section)) {
            return $section;
        };
        return false;
    }


    /**
     * Get reflections by experience id
     *
     * @param object $data
     * @return object | bool
     */
    public static function upsert_reflection_section_text($reflectionid, $group, $content, $id = null)
    {
        global $DB;

        if (!isset($group) || !isset($reflectionid) || !isset($content))
            throw new \Exception('Invalid data');

        if ($id) {
            $data = new stdClass();
            $data->id = $id;
            $data->content = $content;
            $DB->update_record(CONSTANTS::SECTION_TYPES['TEXT']['TABLE'], $data);
            return true;
        }

        $data = new stdClass();
        $data->groupid = CONSTANTS::GROUPS[$group];
        $data->reflectionid = $reflectionid;
        $data->content = $content;
        $data->sectiontype = CONSTANTS::SECTION_TYPES['TEXT']['ID'];
        $data->contentid = $DB->insert_record(CONSTANTS::SECTION_TYPES['TEXT']['TABLE'], $data);

        return self::create_reflection_section($data);
    }

    public static function get_by_experience($experienceid)
    {
        global $DB;
        return $DB->get_record(self::$table, ['experienceid' => $experienceid]);
    }


    /**
     * Get reflections by experience id
     *
     * @param object $data
     * @return array
     */
    private static function get_last_sequence($reflectionid)
    {
        global $DB;
        $tableName = self::$table_section;

        $sql = "SELECT MAX(sequence) AS sequence FROM {" . $tableName . "} WHERE reflectionid = ?";

        $params = [$reflectionid];
        $result = $DB->get_record_sql($sql, $params);

        return $result->sequence;
    }


    /**
     * Create reflection if experience exists and reflection does not exist
     * @param int $experienceid
     * @return bool | object
     */
    public static function create_reflection_if_experience_exist($experienceid)
    {
        if (!parent::experience_exist($experienceid)) return false;
        if ($reflection = self::check_exist_reflection_experience($experienceid)) return $reflection;
        if (!self::experience_exist_own($experienceid)) return false;
        global $USER;
        return self::create_reflection($USER->id, $experienceid);
    }


    /**
     * Check if reflection exists
     * @param int $experienceid
     * @return bool
     */
    public static function check_exist_reflection_experience($experienceid)
    {
        global $DB;
        // Intenta obtener el registro basado en experienceid
        $reflection = $DB->get_record(self::$table, ['experienceid' => $experienceid]);

        // Si $reflection es false, significa que no se encontró el registro
        if (!$reflection) {
            return false;
        }

        // Si se encontró el registro, retorna el objeto $reflection
        return $reflection;
    }

    /**
     * Check if user has permission to create reflection
     * @param int $experienceid
     * @return bool
     */
    public static function experience_exist_own($experienceid)
    {
        global $USER;
        $experience = parent::get_experience_header($experienceid);
        if ($experience->userid != $USER->id) {
            return false;
        }
        return true;
    }

    
    public static function order_by_groups($sections)
    {
        $ordered_sections = new \stdClass();
        foreach (array_keys(CONSTANTS::GROUPS) as $group) {
            $ordered_sections->$group = [];
        }

        foreach ($sections['text'] as $section) {
            $group = array_search($section->groupid, CONSTANTS::GROUPS);
            $ordered_sections->$group[] = $section;
        }

        
        return $ordered_sections;
    }


    /**
     * Get sections by group.
     * @param int $reflection_id The ID associated with the reflection.
     * @param string $group The group of sections to retrieve, or 'ALL' for all sections.
     * @return array An array of sections.
     */
    public static function get_section($reflection_id, $group)
    {
        if ($group == 'ALL') { 
            return self::get_all_sections($reflection_id);
        }
        return self::get_sections_by_group($reflection_id, $group);
    }

    /**
     * Get sections by group.
     * @param int $reflection_id The ID associated with the reflection.
     * @param string $group The group of sections to retrieve, or 'ALL' for all sections.
     * @return array An array of sections.
     */
    public static function get_sections_by_groups($reflection_id, $group)
    {
        $sections = $group ?? 'ALL'  ? self::get_all_sections($reflection_id) : self::get_sections_by_group($reflection_id, $group);
        return self::order_by_groups($sections);
    }

    /**
     * Get all sections for a given reflection ID.
     * @param int $reflection_id The reflection ID.
     * @return array An array of sections.
     */
    private static function get_all_sections($reflection_id)
    {
        $section_text = self::get_section_text($reflection_id, 'ALL');

        $sections = [
            'text' => $section_text
        ];

        return $sections;
    }

    /**
     * Get text sections by group.
     * @param int $reflection_id The reflection ID.
     * @param string $group The group of sections to retrieve, or 'ALL' for all sections.
     * @return mixed The result of the database query to fetch section texts.
     */
    public static function get_section_text($reflection_id, $group = "ALL")
    {
        global $DB;
        $table_section = self::$table_section;
        $table_section_text = CONSTANTS::SECTION_TYPES['TEXT']['TABLE'];
        $sql = "SELECT * FROM {{$table_section}} as t1 INNER JOIN {{$table_section_text}} as t2 ON t1.contentid = t2.id WHERE t1.reflectionid = ?";
        $params = [$reflection_id];
        if ($group != "ALL") {
            $sql .= " AND t1.groupid = ?";
            $params[] = CONSTANTS::GROUPS[$group];
        }

        $sql .= " ORDER BY t1.groupid ASC, t1.sequence ASC";

        return $DB->get_records_sql($sql, $params);
    }

    /**
     * Get sections by a specific group.
     * @param int $reflection_id The reflection ID.
     * @param string $group The group of sections to retrieve.
     * @return array An array of sections.
     */
    private static function get_sections_by_group($reflection_id, $group)
    {
        $sections = [
            'text' => self::get_section_text($reflection_id, $group)
        ];

        return $sections;
    }
}
