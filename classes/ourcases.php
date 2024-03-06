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
    private $db;
    private $id;
    private $experience;
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
            $this->date = $ourcase->date;
            $this->status = $ourcase->status;
        }

    }

    /**
     * Get all cases
     *
     * @return array Returns an array of records
     */
    public static function getCases()
    {
        global $DB;
        $sql = "SELECT * FROM {our_cases}";
        return $DB->get_records_sql($sql);
    }

    /**
     * Get a specific case
     *
     * @param int $id ID of the case
     * @return object Returns a record object
     */
    public static function getCase($id)
    {
        global $DB;
        $sql = "SELECT * FROM {our_cases} WHERE id = ?";
        return $DB->get_record_sql($sql, array($id));
    }

    /**
     * Add a case
     *
     * @param int $experienceid ID of the experience
     * @param string $date Date of the case
     * @param bool $status Status of the case
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function addCase($experienceid, $date, $status = 0)
    {
        global $DB;
        if (empty($experienceid) || empty($date) ) {
            return false;
        }

        // verify if the experience exists
        if(!Experience::getExperience($experienceid)){
            return false;
        }

        $record = new stdClass();
        $record->experience = $experienceid;
        $record->date = $date;
        $record->status = $status;

        if(!$id = $DB->insert_record(self::$table,  $record)){
            throw new Exception('Error adding experience');
        }

        $record->id = $id;

        return $DB->insert_record('our_cases', $record);
        
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
    public static function updateCase($experienceid, $date, $lang, $visible)
    {
        global $DB;
        if (empty($experienceid) ||empty($date) || empty($lang) || empty($visible) ) {
            return false;
        }

        $record = new stdClass();
        $record->id = $id;
        $record->title = $title;
        $record->description = $description;
        $record->date = $date;
        $record->lang = $lang;
        $record->visible = $visible;

        return $DB->update_record('our_cases', $record);
    }

    /**
     * Delete a case
     *
     * @param int $id ID of the case
     * @return bool Returns true if successful, false otherwise
     */
    public static function deleteCase($id)
    {
        global $DB;
        $sql = "DELETE FROM {our_cases} WHERE id = ?";
        return $DB->execute_sql($sql, array($id));
    }
}