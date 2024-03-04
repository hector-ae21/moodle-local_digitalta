<?php

/**
 * Reaction class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_dta;

global $DB;
class Reaction
{
    private static $table_likes = 'experience_likes';
    private static $table_comments = 'experience_comments';
    private static $db;

    public function __construct()
    {
        global $DB; // Hacer $DB accesible dentro de la clase
        self::$db = $DB; // Asignar $DB a la propiedad estática $db
    }

    /**
     * Add a 'like' reaction to an experience
     *
     * @param int $experienceId ID of the experience
     * @param int $userId ID of the user
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function addLike($experienceId, $userId)
    {
        $record = new stdClass();
        $record->experience_id = $experienceId;
        $record->user_id = $userId;
        $record->reaction_type = 'like';

        return self::$db->insert_record(self::$table_likes, $record);
    }

    /**
     * Add an 'unlike' reaction to an experience
     *
     * @param int $experienceId ID of the experience
     * @param int $userId ID of the user
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function addUnlike($experienceId, $userId)
    {
        $record = new stdClass();
        $record->experience_id = $experienceId;
        $record->user_id = $userId;
        $record->reaction_type = 'unlike';

        return  self::$db->insert_record(self::$table_likes, $record);
    }

    /**
     * Add a comment to an experience
     *
     * @param int $experienceId ID of the experience
     * @param int $userId ID of the user
     * @param string $comment The comment text
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function addComment($experienceId, $userId, $comment)
    {
        $record = new stdClass();
        $record->experience_id = $experienceId;
        $record->user_id = $userId;
        $record->comment = $comment;

        return self::$db->insert_record(self::$table_comments, $record);
    }

    /**
     * Get all 'like' reactions for a specific experience
     *
     * @param int $experienceId ID of the experience
     * @return array Returns an array of records
     */
    public static function getLikesForExperience($experienceId)
    {
        $sql = "SELECT * FROM {" . self::$table_likes . "} WHERE experience_id = ? AND reaction_type = 'like'";
        return self::$db->get_records_sql($sql, array($experienceId));
    }

    /**
     * Get all 'unlike' reactions for a specific experience
     *
     * @param int $experienceId ID of the experience
     * @return array Returns an array of records
     */
    public static function getUnlikesForExperience($experienceId)
    {
        $sql = "SELECT * FROM {" . self::$table_likes . "} WHERE experience_id = ? AND reaction_type = 'unlike'";
        return self::$db->get_records_sql($sql, array($experienceId));
    }

    /**
     * Get all comments for a specific experience
     *
     * @param int $experienceId ID of the experience
     * @return array Returns an array of records
     */
    public static function getCommentsForExperience($experienceId)
    {
        $sql = "SELECT * FROM {" . self::$table_comments . "} WHERE experience_id = ?";
        return self::$db->get_records_sql($sql, array($experienceId));
    }

    /**
     * Remove a 'like' reaction from an experience
     *
     * @param int $experienceId ID of the experience
     * @param int $userId ID of the user
     * @return bool Returns true if successful, false otherwise
     */
    public static function removeLike($experienceId, $userId)
    {
        $conditions = array(
            'experience_id' => $experienceId,
            'user_id' => $userId,
            'reaction_type' => 'like'
        );
        return self::$db->delete_records(self::$table_likes, $conditions);
    }

    /**
     * Remove an 'unlike' reaction from an experience
     *
     * @param int $experienceId ID of the experience
     * @param int $userId ID of the user
     * @return bool Returns true if successful, false otherwise
     */
    public static function removeUnlike($experienceId, $userId)
    {
        $conditions = array(
            'experience_id' => $experienceId,
            'user_id' => $userId,
            'reaction_type' => 'unlike'
        );
        return self::$db->delete_records(self::$table_likes, $conditions);
    }

    /**
     * Remove a comment from an experience
     *
     * @param int $commentId ID of the comment to remove
     * @return bool Returns true if successful, false otherwise
     */
    public static function removeComment($commentId)
    {
        return self::$db->delete_records(self::$table_comments, array('id' => $commentId));
    }
}
