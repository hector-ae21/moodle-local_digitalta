<?php
/**
 * Tag class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_dta;

use stdClass;

class Tags
{
    private $db;

    /**
     * Class constructor.
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Get all tags
     *
     * @return array|null
     */
    public static function getAllTags()
    {
        global $DB;
        $tags = array_values($DB->get_records('digital_tags'));
        return $tags;
    }

    /**
     * Get a tag by its ID
     *
     * @param int $id
     * @return stdClass|null
     */
    public function getTag($id)
    {
        $sql = "SELECT * FROM {digital_tags} WHERE id = ?";
        return $this->db->get_record_sql($sql, array($id));
    }

    /**
     * Add a new tag
     *
     * @param string $tag
     * @return bool
     */
    public static function addTag($tag)
    {
        global $DB;
        if (empty($tag)) {
            return false;
        }
        if ($id = $DB->get_field('digital_tags', 'id', array('name' => $tag))) {
            return $id;
        }
        $record = new \stdClass();
        $record->name = $tag;
        return $DB->insert_record('digital_tags', $record);
    }

    /**
     * Update an existing tag
     *
     * @param int $id
     * @param string $tag
     * @return bool
     */
    public function updateTag($id, $tag)
    {
        if (empty($tag)) {
            return false;
        }

        $record = new stdClass();
        $record->id = $id;
        $record->tag = $tag;

        return $this->db->update_record('digital_tags', $record);
    }

    /**
     * Delete an existing tag
     *
     * @param int $id
     * @return bool
     */
    public function deleteTag($id)
    {
        return $this->db->delete_records('digital_tags', array('id' => $id));
    }


    /**
     * Get all tags by text
     *
     * @param string $text
     * @return stdClass|null
     */
    public static function getTagsByText($text)
    {
        global $DB;
        $tags = array_values($DB->get_records_sql('SELECT * FROM {digital_tags} WHERE name LIKE ?', array($text)));
        return $tags;
    }
}
