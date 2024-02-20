<?php
/**
 * reflection questions class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 class reflection_question {

    private $db;
    private static $table = 'reflection_questions';

    /**
     * Class constructor.
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Get a question by its ID
     *
     * @param int $id
     * @return stdClass|null
     */
    public function getQuestion($id)
    {
        $sql = "SELECT * FROM " . self::$table . " WHERE id = ?";
        return $this->db->get_record_sql($sql, array($id));
    }


    /**
     * Get all questions
     *
     * @param int $id
     * @return stdClass|null
     */
    public function getQuestions()
    {
        $sql = "SELECT * FROM " . self::$table . " ORDER BY id ASC";
        return $this->db->get_record_sql($sql);
    }

    /**
     * Add a new question
     *
     * @param string $question
     * @return bool
     */
    public function addQuestion($question)
    {
        if (empty($question)) {
            return false;
        }

        $record = new stdClass();
        $record->tag = $question;

        return $this->db->insert_record(self::$table , $record);
    }

    
    /**
     * Update an existing question
     *
     * @param int $id
     * @param string $question
     * @return bool
     */
    public function updateQuestion($id, $question)
    {
        if (empty($question)) {
            return false;
        }

        $record = new stdClass();
        $record->id = $id;
        $record->tag = $question;

        return $this->db->update_record(self::$table , $record);
    }

    /**
     * Delete an existing question
     *
     * @param int $id
     * @return bool
     */
    public function deleteQuestion($id)
    {
        return $this->db->delete_records(self::$table , array('id' => $id));
    }

 }