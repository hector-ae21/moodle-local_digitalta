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

use stdClass;

class Reflection extends Experience
{

    private static $table = 'digital_reflection';

    private static $table_section = 'digital_refl_sections';


    /**
     * Get reflections by experience id
     *
     * @param object $data
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
     * @return bool
     */
    public static function create_reflection_if_experience_exist($experienceid)
    {
        if (!parent::check_experience($experienceid)) return false;
        if ($reflection = self::check_exist_reflection_experience($experienceid)) return $reflection;
        if (!self::check_experience_own($experienceid)) return false;
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
    public static function check_experience_own($experienceid)
    {
        global $USER;
        $experience = parent::get_experience($experienceid);
        if ($experience->userid != $USER->id) {
            return false;
        }
        return true;
    }
}
