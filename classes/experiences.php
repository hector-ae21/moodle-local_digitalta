<?php

/**
 * Experience class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class Experience
{
    private static $table = 'digital_experiences';
    private $db;
    private $id;
    private $title;
    private $description;
    private $date;
    private $lang;
    private $visible;


    /**
     * Class constructor.
     */
    public function __construct($experience = null)
    {
        global $DB;
        $this->db = $DB;
        if ($experience && is_object($experience)) {
            $this->id = $experience->id;
            $this->title = $experience->title;
            $this->description = $experience->description;
            $this->date = $experience->date;
            $this->lang = $experience->lang;
            $this->visible = $experience->visible;
        }
    }

    /**
     * Get an experience by this id
     *
     * @return stdClass|null
     */
    public function getExperience()
    {
        $sql = "SELECT * FROM {digital_experiences} WHERE id = ?";
        return $this->db->get_record_sql($sql, array($this->id));
    }

    /**
     * Add a new experience to the database and return the new experience
     *
     * @param string $title
     * @param string $description
     * @param string $date
     * @param string $lang
     * @param bool $visible
     * @return Experience|bool
     */
    public static function addExperience($title, $description, $date, $lang, $visible)
    {
        global $DB;
        if (empty($title) || empty($description) || empty($date) || empty($lang)) {
            return false;
        }

        $record = new stdClass();
        $record->title = $title;
        $record->description = $description;
        $record->date = $date;
        $record->lang = $lang;
        $record->visible = $visible;

        $id = $DB->insert_record(self::$table, $record);
        
        if($id === 0) {
            throw new Exception('Error adding experience'); 
        }

        $record->id = $id;
        
        return new Experience($record);
    }

    /**
     * Update an existing experience
     *
     * @param object $experience
     * @return bool
     */
    public static function updateExperience($experience)
    {
        if (empty($experience->id)) {
            throw new Exception('Error id not found');
        }

        if (empty($experience->title) || empty($experience->description) || empty($experience->date) || empty($experience->lang) || !isset($experience->visible)) {
            throw new Exception('Error experience properties not found or incomplete');
        }

        global $DB;

        $record = new stdClass();
        $record->id = $experience->id;
        $record->title = $experience->title;
        $record->description = $experience->description;
        $record->date = $experience->date;
        $record->lang = $experience->lang;
        $record->visible = $experience->visible;

        return $DB->update_record(self::$table, $record);
    }
}
