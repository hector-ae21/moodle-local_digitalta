<?php

/**
 * Reaction class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class Reaction
{
    private static $table_likes = 'experience_likes';
    private static $table_comments = 'experience_comments';
    
    /**
     * Add a 'like' reaction to an experience
     *
     * @param object $db Database connection object
     * @param int $experienceId ID of the experience
     * @param int $userId ID of the user
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function addLike($db, $experienceId, $userId)
    {
        $record = new stdClass();
        $record->experience_id = $experienceId;
        $record->user_id = $userId;
        $record->reaction_type = 'like';

        return $db->insert_record(self::$table_likes, $record);
    }

    /**
     * Add an 'unlike' reaction to an experience
     *
     * @param object $db Database connection object
     * @param int $experienceId ID of the experience
     * @param int $userId ID of the user
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function addUnlike($db, $experienceId, $userId)
    {
        $record = new stdClass();
        $record->experience_id = $experienceId;
        $record->user_id = $userId;
        $record->reaction_type = 'unlike';

        return $db->insert_record(self::$table_likes, $record);
    }

    /**
     * Add a comment to an experience
     *
     * @param object $db Database connection object
     * @param int $experienceId ID of the experience
     * @param int $userId ID of the user
     * @param string $comment The comment text
     * @return bool|int Returns ID of the inserted record if successful, false otherwise
     */
    public static function addComment($db, $experienceId, $userId, $comment)
    {
        $record = new stdClass();
        $record->experience_id = $experienceId;
        $record->user_id = $userId;
        $record->comment = $comment;

        return $db->insert_record(self::$table_comments, $record);
    }

    /**
     * Get all 'like' reactions for a specific experience
     *
     * @param object $db Database connection object
     * @param int $experienceId ID of the experience
     * @return array Returns an array of records
     */
    public static function getLikesForExperience($db, $experienceId)
    {
        $sql = "SELECT * FROM {" . self::$table_likes . "} WHERE experience_id = ? AND reaction_type = 'like'";
        return $db->get_records_sql($sql, array($experienceId));
    }

    /**
     * Get all 'unlike' reactions for a specific experience
     *
     * @param object $db Database connection object
     * @param int $experienceId ID of the experience
     * @return array Returns an array of records
     */
    public static function getUnlikesForExperience($db, $experienceId)
    {
        $sql = "SELECT * FROM {" . self::$table_likes . "} WHERE experience_id = ? AND reaction_type = 'unlike'";
        return $db->get_records_sql($sql, array($experienceId));
    }

    /**
     * Get all comments for a specific experience
     *
     * @param object $db Database connection object
     * @param int $experienceId ID of the experience
     * @return array Returns an array of records
     */
    public static function getCommentsForExperience($db, $experienceId)
    {
        $sql = "SELECT * FROM {" . self::$table_comments . "} WHERE experience_id = ?";
        return $db->get_records_sql($sql, array($experienceId));
    }

    /**
     * Remove a 'like' reaction from an experience
     *
     * @param object $db Database connection object
     * @param int $experienceId ID of the experience
     * @param int $userId ID of the user
     * @return bool Returns true if successful, false otherwise
     */
    public static function removeLike($db, $experienceId, $userId)
    {
        $conditions = array(
            'experience_id' => $experienceId,
            'user_id' => $userId,
            'reaction_type' => 'like'
        );
        return $db->delete_records(self::$table_likes, $conditions);
    }

    /**
     * Remove an 'unlike' reaction from an experience
     *
     * @param object $db Database connection object
     * @param int $experienceId ID of the experience
     * @param int $userId ID of the user
     * @return bool Returns true if successful, false otherwise
     */
    public static function removeUnlike($db, $experienceId, $userId)
    {
        $conditions = array(
            'experience_id' => $experienceId,
            'user_id' => $userId,
            'reaction_type' => 'unlike'
        );
        return $db->delete_records(self::$table_likes, $conditions);
    }

    /**
     * Remove a comment from an experience
     *
     * @param object $db Database connection object
     * @param int $commentId ID of the comment to remove
     * @return bool Returns true if successful, false otherwise
     */
    public static function removeComment($db, $commentId)
    {
        return $db->delete_records(self::$table_comments, array('id' => $commentId));
    }
}
