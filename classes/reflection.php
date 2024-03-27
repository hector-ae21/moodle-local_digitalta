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

use stdClass;

class Reflection extends Experience
{
    /*
    * Reflection section types
    */
    const SECTION_TYPES = [
        "TEXT" => [
            "ID" => 1,
            "TABLE" => "digital_refl_sec_text",
        ]
    ];

    /*
    * Reflection group types
    */
    const GROUPS = [
        "WHAT" => 1 ,
        "SO_WHAT" => 2,
        "NOW_WHAT" => 3,
        "EXTRA" => 4 
    ]; 

    // Reflection table
    private static $table = 'digital_reflection';
    // Reflection section table
    private static $table_section = 'digital_refl_sections';

    /**
     * Get reflections by experience id
     *
     * @param object $data
     * @return array
     */
    public static function create_reflection($data)
    {
        global $DB;

        if(!isset($data->userid) || !isset($data->experienceid) || !isset($data->reflection))
            throw new \Exception('Invalid data');

        $reflection = new stdClass();
        $reflection->experienceid = $data->experienceid;
        $reflection->userid = $data->userid;
        $reflection->timecreated = time();
        $reflection->timemodified = time();
        $reflection->status = 0;
        $reflection->visible = 0;
        $reflection->reflection = $data->reflection;
        $reflection->date = time();

        if($DB->insert_record(self::$table, $reflection)){
            return true;
        };
        return false;
    }

    

    private static function create_reflection_section($data)
    {
        global $DB;

        if(!isset($data->reflectionid) || !isset($data->sectiontype) || !isset($data->contentid) || !isset($data->groupid) )
            throw new \Exception('Invalid data');

        $section = new stdClass();
        $section->reflectionid = $data->reflectionid;
        $section->groupid = $data->groupid;
        $section->sequence = $data->sequence;
        $section->sectiontype = $data->sectiontype;
        $section->contentid = $data->contentid;

        if($DB->insert_record(self::$table_section, $section)){
            return true;
        };
        return false;
    }


    /*
    * Get las sequence of a reflection
    */
    private static function get_last_sequence($reflectionid)
    {
        global $DB;

        $sql = "SELECT MAX(sequence) as sequence FROM {self::$table_section} WHERE reflectionid = ?";
        $params = [$reflectionid];
        $result = $DB->get_record_sql($sql, $params);

        return $result->sequence;
    }





}