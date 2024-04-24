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
    private static $table_experience_reports = 'digital_experience_report';
    
    private static $table_cases_likes = 'digital_case_likes';
    private static $table_cases_comments = 'digital_case_comments';
    private static $table_cases_reports = 'digital_case_report';

    public function __construct()
    {
    }

    /**
     * Get all reactions for a specific experience
     *
     * @param int $experienceid ID of the experience
     * @return array Returns an array of records
     */
    public static function get_reactions_for_render_experience($experienceid)
    {
        $likes = self::get_likes_for_experience($experienceid);
        $unlikes = self::get_unlikes_for_experience($experienceid);
        $comments = self::get_comments_for_experience($experienceid);
        $reports = self::get_reports_for_experience($experienceid);

        return [
            'likes' => [
                'count' => count($likes),
                'isactive' => self::user_reacted($likes),
                'data' => $likes
            ],
            'dislikes' => [
                'count' => count($unlikes),
                'data' => $unlikes,
                'isactive' => self::user_reacted($unlikes)
            ],
            'comments' => [
                'count' => count($comments),
                'data' => $comments
            ],
            'reports' => [
                'count' => count($reports),
                'data' => $reports,
                'isactive' => self::user_reacted($reports)
            ]
        ];
    }

    /**
     * Get all reactions for a specific case
     *
     * @param int $caseid ID of the case
     * @return array Returns an array of records
     */
    public static function get_reactions_for_render_case($caseid)
    {
        $likes = self::get_likes_for_case($caseid);
        $unlikes = self::get_unlikes_for_case($caseid);
        $comments = self::get_comments_for_case($caseid);
        $reports = self::get_reports_for_case($caseid);
        
        return [
            'likes' => [
                'count' => count($likes),
                'isactive' => self::user_reacted($likes),
                'data' => $likes
            ],
            'dislikes' => [
                'count' => count($unlikes),
                'data' => $unlikes,
                'isactive' => self::user_reacted($unlikes)
            ],
            'comments' => [
                'count' => count($comments),
                'data' => $comments
            ],
            'reports' => [
                'count' => count($reports),
                'data' => $reports,
                'isactive' => self::user_reacted($reports)
            ]
        ];
    }

    

    /**
     * Check if the current user has reacted to a specific experience
     * 
     * @param array $reactionData The reaction data
     * @return bool Returns true if the user has reacted to the experience
     */
    public static function user_reacted($reactionsData)
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
        $sql = "SELECT * FROM {" . self::$table_experience_comments . "} WHERE experienceid = ?";
        return $DB->get_records_sql($sql, array($experienceid));
    }

    public static function get_reports_for_experience($experienceid)
    {
        global $DB;
        $sql = "SELECT * FROM {" . self::$table_experience_reports . "} WHERE experienceid = ?";
        return $DB->get_records_sql($sql, array($experienceid));
    }

    /**
     * Get all 'like' reactions for a specific experience
     *
     * @param int $experienceid ID of the experience
     * @return array Returns an array of records
     */
    public static function get_likes_for_case($caseid)
    {
        global $DB;
        $sql = "SELECT * FROM {" . self::$table_cases_likes . "} WHERE caseid = ? AND reactiontype = '1'";
        return $DB->get_records_sql($sql, array($caseid));
    }

    /**
     * Get all 'unlike' reactions for a specific experience
     *
     * @param int $experienceid ID of the experience
     * @return array Returns an array of records
     */
    public static function get_unlikes_for_case($caseid)
    {
        global $DB;
        $sql = "SELECT * FROM {" . self::$table_cases_likes . "} WHERE caseid = ? AND reactiontype = '0'";
        return $DB->get_records_sql($sql, array($caseid));
    }

    /**
     * Get all comments for a specific experience
     *
     * @param int $experienceid ID of the experience
     * @return array Returns an array of records
     */
    public static function get_comments_for_case($caseid)
    {
        global $DB;
        $sql = "SELECT * FROM {" . self::$table_cases_comments . "} WHERE caseid = ?";

        return $DB->get_records_sql($sql, array($caseid)); 
    }

    /**
     * Get all reports for a specific experience
     * 
     * @param int $caseid ID of the case
     * @return array Returns an array of records
     */
    public static function get_reports_for_case($caseid)
    {
        global $DB;
        $sql = "SELECT * FROM {" . self::$table_cases_reports . "} WHERE caseid = ?";

        return $DB->get_records_sql($sql, array($caseid)); 
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
        $sql = "SELECT e.id, e.userid, e.title, e.description, e.timecreated, e.lang, e.visible, e.status, COUNT(l.id) as likes
                FROM {digital_experiences} e
                LEFT JOIN {digital_experience_likes} l ON e.id = l.experienceid
                WHERE l.reactiontype = 1 AND e.visible = 1
                GROUP BY e.id
                ORDER BY likes DESC
                LIMIT ". $limit;

        return array_values($DB->get_records_sql($sql));
    }
}
