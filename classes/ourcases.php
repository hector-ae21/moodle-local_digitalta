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
    public $id;
    private $experienceid;
    private $userid;
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
            $this->experienceid = $ourcase->experienceid;
            $this->userid = $ourcase->userid;
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
        $cases = $DB->get_records(self::$table);
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
        $cases = $DB->get_records(self::$table, ['status' => 1]);
        $cases = self::get_extra_fields($cases);
        return $cases;
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
        return $DB->get_record(self::$table, ['experienceid' => $experience]);
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
        return $DB->get_records(self::$table, ['experienceid' => $experience]);
    }
    /**
     * Add a case
     *
     * @param int $experienceid ID of the experience
     * @param string $date Date of the case
     * @param bool $status Status of the case
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function add_with_experience($experienceid, $date, $userid, $status = 0)
    {
        global $DB;
        if (empty($experienceid) || empty($date) || empty($userid)) {
            return false;
        }

        // verify if the experience exists
        if (!$experience = Experience::get_experience($experienceid)) {
            return false;
        }

        $record = new stdClass();
        $record->experienceid = $experienceid;
        $record->userid = $userid;
        $record->date = $date;
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
     * @param string $date Date of the case
     * @param bool $status Status of the case
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function add_without_experience($date, $userid, $status = 0)
    {
        global $DB;
        if (empty($date) || empty($userid)) {
            return false;
        }

        if($existing = $DB->get_record(self::$table, ['userid' => $userid, 'status' => $status , 'experienceid' => 0])){
            return $existing;
        }

        
        $record = new stdClass();
        $record->experienceid = 0;
        $record->userid = $userid;
        $record->date = $date;
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

    public static function get_extra_fields($cases)
    {
        global $PAGE, $DB;
        foreach ($cases as $case) {
            $user = get_complete_user_data("id", $case->userid);
            $picture = new \user_picture($user);
            $picture->size = 101;
            $case->date = date("d/m/Y", strtotime($case->date));
            $case->user = [
                'id' => $user->id,
                'name' => $user->firstname . " " . $user->lastname,
                'email' => $user->email,
                'imageurl' => $picture->get_url($PAGE)->__toString(),
                'profileurl' => new \moodle_url('/user/profile.php', ['id' => $user->id])
            ];
            $case->reactions = Reaction::get_reactions_for_render_experience($case->id);
        }
        return $cases;
    }
}
