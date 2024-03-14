<?php

/**
 * ExperienceTag class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

class ExperienceTag
{

    private static $table = 'experience_tag';

    
    /**
     * Assign a tag to an experience
     *
     * @param int $experienceId
     * @param int $tagId
     * @return bool
     */
    public static function assignTagToExperience($experienceId, $tagId, $db)
    {
        $record = new stdClass();
        $record->experience_id = $experienceId;
        $record->tag_id = $tagId;

        return $db->insert_record(self::$table, $record);
    }

    /**
     * Remove a tag from an experience
     *
     * @param int $experienceId
     * @param int $tagId
     * @return bool
     */
    public static function removeTagFromExperience($experienceId, $tagId, $db)
    {
        $conditions = array('experienceid' => $experienceId, 'tagid' => $tagId);
        return $db->delete_records(self::$table, $conditions);
    }

    /**
     * Get all tags associated with an experience
     *
     * @param int $experienceId
     * @return array
     */
    public static function getTagsForExperience($experienceId, $db)
    {
        $sql = "SELECT t.*
                FROM {digital_tags} t
                JOIN {experience_tag} et ON t.id = et.tagid
                WHERE et.experienceid = ?";
        return $db->get_records_sql($sql, array($experienceId));
    }

    /**
     * Get all experiences associated with a tag
     * 
     * @param int $tagId
     * @return array
     */
    public static function getExperiencesForTag($tagId, $db) {
        $sql = "SELECT e.*
                FROM {digital_experience} e
                JOIN {experience_tag} et ON e.id = et.experienceid
                WHERE et.tagid = ?";
        return $db->get_records_sql($sql, array($tagId));
    }
}
