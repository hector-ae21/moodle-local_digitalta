<?php

/**
 * Reaction class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_dta;

class Reaction
{
    private static $table_experience_likes = 'digital_experience_likes';
    private static $table_experience_comments = 'digital_experience_comments';

    public function __construct()
    {
    }

    public static function get_reactions_for_render_experience($experienceid)
    {
        global $USER;
        $likes = self::get_likes_for_experience($experienceid);
        $unlikes = self::get_unlikes_for_experience($experienceid);
        $comments = self::get_comments_for_experience($experienceid);

        return [
            'likes' => [
                'count' => count($likes),
                'isactive' => self::user_reacted_experience($experienceid, $likes),
                'data' => $likes
            ],
            'dislikes' => [
                'count' => count($unlikes),
                'data' => $unlikes,
                'isactive' => self::user_reacted_experience($experienceid, $unlikes)
            ],
            'comments' => [
                'count' => count($comments),
                'data' => $comments
            ]
        ];
    }

    /**
     * Check if the current user has reacted to a specific experience
     * 
     * @param int $experienceid ID of the experience
     * @param array $reactionData The reaction data
     * @return bool Returns true if the user has reacted to the experience
     */
    public static function user_reacted_experience($experienceid, $reactionsData)
{
    global $USER;

    foreach ($reactionsData as $reaction) {
        if ($reaction->userid == $USER->id) {
            return true;
        }
    }

    return false;
}


    /**
     * Get all 'like' reactions for a specific experience
     *
     * @param int $experienceid ID of the experience
     * @return array Returns an array of records
     */
    public static function get_likes_for_experience($experienceid)
    {
        global $DB;
        $sql = "SELECT * FROM {" . self::$table_experience_likes . "} WHERE experienceid = ? AND reactiontype = '1'";
        return $DB->get_records_sql($sql, array($experienceid));
    }

    /**
     * Get all 'unlike' reactions for a specific experience
     *
     * @param int $experienceid ID of the experience
     * @return array Returns an array of records
     */
    public static function get_unlikes_for_experience($experienceid)
    {
        global $DB;
        $sql = "SELECT * FROM {" . self::$table_experience_likes . "} WHERE experienceid = ? AND reactiontype = '0'";
        return $DB->get_records_sql($sql, array($experienceid));
    }

    /**
     * Get all comments for a specific experience
     *
     * @param int $experienceid ID of the experience
     * @return array Returns an array of records
     */
    public static function get_comments_for_experience($experienceid)
    {
        global $DB;
        $sql = "SELECT * FROM {" . self::$table_experience_likes . "} WHERE experienceid = ?";
        return $DB->get_records_sql($sql, array($experienceid));
    }
    
    /**
     * Get the most liked experiences
     *
     * @param int $limit The number of experiences to return
     * @return array Returns an array of records
     */
    public static function get_most_liked_experience($limit = 5)
    {
        global $DB;
        $sql = "SELECT e.id, e.title, e.description, COUNT(l.id) as likes
                FROM {digital_experiences} e
                LEFT JOIN {digital_experience_likes} l ON e.id = l.experienceid
                WHERE l.reactiontype = 1
                GROUP BY e.id
                ORDER BY likes DESC
                LIMIT ?";
        return $DB->get_records_sql($sql, array($limit));
    }

    
}
