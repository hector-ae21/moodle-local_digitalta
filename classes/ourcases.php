<?php

/**
 * OurCases class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

use stdClass;

class OurCases
{
    private static $table = 'digital_ourcases';
    private static $table_section_text = 'digital_oc_sec_text';
    private $db;
    private $id;
    private $experience;
    private $user;
    private $date;
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
            $this->experience = $ourcase->experience;
            $this->user = $ourcase->user;
            $this->date = $ourcase->date;
            $this->status = $ourcase->status;
        }
    }
    /**
     * Get all cases
     *
     * @return array Returns an array of records
     */
    public static function get_cases()
    {
        global $DB;
        return $DB->get_records(self::$table);
    }
    /**
     * Get a specific case
     *
     * @param int $id ID of the case
     * @return object Returns a record object
     */
    public static function get_case($id)
    {
        global $DB;
        return $DB->get_record(self::$table, ['id' => $id]);
    }

    /**
     * Get a specific case by experience
     *
     * @param int $id ID of the case
     * @return object Returns a record object
     */
    public static function get_case_by_experience($experience)
    {
        global $DB;
        return $DB->get_record(self::$table, ['experience' => $experience]);
    }
    /**
     * Add a case
     *
     * @param int $experienceid ID of the experience
     * @param string $date Date of the case
     * @param bool $status Status of the case
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function add_case($experienceid, $date, $user, $status = 0)
    {
        global $DB;
        if (empty($experienceid) || empty($date) || empty($user)) {
            return false;
        }

        // verify if the experience exists
        if (!$experience = Experience::getExperience($experienceid)) {
            return false;
        }

        $record = new stdClass();
        $record->experience = $experienceid;
        $record->user = $user;
        $record->date = $date;
        $record->status = $status;

        if (!$id = $DB->insert_record(self::$table,  $record)) {
            throw new Exception('Error adding ourcase to the database.');
        }

        $record->id = $id;

        // adding default section text
        $reord_section = new stdClass();
        $reord_section->ourcase = $id;
        $reord_section->title = $experience->title;
        $reord_section->description = $experience->description;
        $reord_section->sequence = 0;

        if (!$DB->insert_record(self::$table_section_text,  $reord_section)) {
            throw new Exception('Error adding ourcase section text to the database.');
        }


        return new OurCases($record);
    }

    /**
     * Update a case
     *
     * @param int $id ID of the case
     * @param string $title Title of the case
     * @param string $description Description of the case
     * @param string $date Date of the case
     * @param string $lang Language of the case
     * @param bool $visible Visibility of the case
     * @return bool Returns true if successful, false otherwise
     */
    public static function update_case($experienceid, $date, $lang, $visible)
    {
        global $DB;
        if (empty($experienceid) || empty($date) || empty($lang) || empty($visible)) {
            return false;
        }

        $record = new stdClass();
        $record->id = $id;
        $record->title = $title;
        $record->description = $description;
        $record->date = $date;
        $record->lang = $lang;
        $record->visible = $visible;

        return $DB->update_record(self::$table, $record);
    }

    /**
     * Delete a case
     *
     * @param int $id ID of the case
     * @return bool Returns true if successful, false otherwise
     */
    public static function delete_case($id)
    {
        global $DB;
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

        $sql = "SELECT * FROM {" . self::$table_section_text . "} WHERE ourcase = ? ";
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
        return $DB->get_record(self::$table_section_text, ['ourcase' => $ourcase, 'sequence' => 0]);
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
}
