<?php

/**
 * cases tags class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 

namespace local_dta;

class CasesTags
{

    private static $table = 'digital_cases_tag';

    
    /**
     * Assign a tag to a case
     * @param int $caseId
     * @param int $tagId
     * @return bool
     */
    public static function assign_tag_to_case($caseId, $tagId)
    {
        global $DB;
        $record = new \stdClass();
        $record->caseid = $caseId;
        $record->tagid = $tagId;
        return $DB->insert_record(self::$table, $record);
    }


    /**
     * Update case tags
     * @param int $caseId
     * @param array $tags
     * @return void
     */
    public static function update_case_tags($caseId, $tags)
    {
        global $DB;
        $tags = array_map(function ($tag) {
            return (int)$tag;
        }, $tags);

        $currentTags = self::get_tags_for_case($caseId, $DB);
        $currentTagIds = array_map(function ($tag) {
            return $tag->id;
        }, $currentTags);

        foreach ($currentTags as $tag) {
            if (!in_array($tag->id, $tags)) {
                self::remove_tag_from_case($caseId, $tag->id, $DB);
            }
        }

        foreach ($tags as $tag) {
            if (!in_array($tag, $currentTagIds)) {
                self::assign_tag_to_case($caseId, $tag);
            }
        }
    }

    /**
     * Get tags for a case
     * @param int $caseId
     * @param object $DB
     * @return array
     */
    public static function get_tags_for_case($caseId)
    {
        global $DB;
        return $DB->get_records_sql('SELECT t.id, t.name FROM {digital_tags} t JOIN {digital_cases_tag} ct ON t.id = ct.tagid WHERE ct.caseid = ?', [$caseId]);
    }

    /**
     * Remove a tag from a case
     * @param int $caseId
     * @param int $tagId
     * @param object $db
     * @return bool
     */
    public static function remove_tag_from_case($caseId, $tagId, $db)
    {
        $conditions = array('caseid' => $caseId, 'tagid' => $tagId);
        return $db->delete_records(self::$table, $conditions);
    }

}
