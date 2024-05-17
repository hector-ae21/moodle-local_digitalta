<?php

/**
 * Mentors class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_dta;

require_once($CFG->dirroot . '/local/dta/classes/chat/chat.php');

use local_dta\Chat;

class Mentor
{
    private $id;
    private static $table = 'user';

    private static $mentor_request_table = 'digital_mentoring_request';

    /**
     * Get a mentor by its ID.
     * 
     * @param $id int The ID of the mentor.
     * 
     * @return object The mentor object.
     */
    public static function get_mentor(int $id): object
    {
        global $DB;
        return $DB->get_record(self::$table, ['id' => $id]);
    }

    /**
     * Get a mentor by its ID.
     * 
     * @param $id int The ID of the mentor.
     */
    public static function get_request(int $id): object | bool
    {
        global $DB;
        return $DB->get_record(self::$mentor_request_table, ['id' => $id]);
    }

    /**
     * Get all mentors.
     * 
     * @return array The mentors.
     */
    public static function get_all_mentors(): array
    {
        global $DB;
        return array_values($DB->get_records(self::$table));
    }
    /**
     * Send a mentor request.
     * 
     * @param $mentorid int The ID of the mentor.
     * @param $userid int The ID of the user.
     * @param $experienceid int The ID of the experience.
     * 
     * @return bool|object The mentor request object.
     */
    public static function send_mentor_request(int $mentorid, int $experienceid): bool|object
    {
        global $DB;
        $data = new \stdClass();
        $data->mentorid = $mentorid;
        $data->experienceid = $experienceid;
        $data->status = 0;
        $data->timecreated = date('Y-m-d H:i:s', time());
        if (!$data->id = $DB->insert_record(self::$mentor_request_table, $data)) {
            return false;
        }
        return $data;
    }

    /**
     * Get mentor requests by mentor ID.
     * 
     * @param $mentorid int The ID of the mentor.
     * @param $status int The status of the mentor request.
     * 
     * @return array The mentor requests.
     */
    public static function get_mentor_requests_by_mentor(int $mentorid, $status = 0): array
    {
        global $DB;
        return array_values($DB->get_records(self::$mentor_request_table, ['mentorid' => $mentorid , 'status' => $status]));
    }

    /**
     * Get mentor requests by experience ID.
     * 
     * @param $experienceid int The ID of the experience.
     * 
     * @return array The mentor requests.
     */
    public static function get_mentor_requests_by_experience(int $experienceid, int $status = 1): array
    {
        global $DB;
        return array_values($DB->get_records(self::$mentor_request_table, ['experienceid' => $experienceid, 'status' => $status]));
    }

    /**
     * Change the status of a mentor request.
     * 
     * @param $id int The ID of the mentor request.
     * @param $status int The status of the mentor request.
     * 
     * @return bool True if the status was changed, false otherwise.
     */
    public static function change_mentor_request_status(int $id, int $status): bool
    {
        global $DB;
        $data = new \stdClass();
        $data->id = $id;
        $data->status = $status;
        return $DB->update_record(self::$mentor_request_table, $data);
    }

    /**
     * Accept a mentor request.
     * 
     * @param $requestid int The ID of the mentor request.
     */
    public static function accept_mentor_request(int $requestid): bool
    {
        $request = self::get_request($requestid);
        if (!$request) {
            return false;
        }

        $chat = Chat::get_chat_room_by_experience($request->experienceid);

        if (!$chat) {
            $chat = new \stdClass();
            $chat->id = Chat::create_chat_room($request->experienceid);
        }
        
        Chat::add_user_to_chat_room($chat->id, $request->mentorid);

        return self::change_mentor_request_status($requestid, 1);
    }

    /**
     * Reject a mentor request.
     * 
     * @param $requestid int The ID of the mentor request.
     */
    public static function reject_mentor_request(int $requestid): bool
    {
        return self::remove_mentor_request_by_id($requestid);
    }

    /**
     * Get a chunk of mentors.
     * 
     * @param $numLoaded int The number of mentors already loaded.
     * @param $numToLoad int The number of mentors to load.
     * 
     * @return object The mentors.
     * 
     */
    public static function get_mentor_chunk(int $numLoaded, int $numToLoad): array
    {
        global $DB;
        return $DB->get_records_sql("SELECT * FROM {" . self::$table . "} LIMIT $numToLoad OFFSET $numLoaded");
    }

    /**
     * Verify if a mentor request exists.
     * 
     * @param $mentorid int The ID of the mentor.
     * @param $experienceid int The ID of the experience.
     * @param $status int The status of the mentor request.
     */
    public static function is_enrolled_mentor_in_course(int $mentorid, int $experienceid, int $status = 1): bool
    {
        global $DB;
        $data = $DB->get_record(self::$mentor_request_table, ['mentorid' => $mentorid, 'experienceid' => $experienceid , 'status' => $status]);
        if (!$data) {
            return false;
        }
        return $data->status == 0;
    }

    /**
     * Remove a mentor request.
     * 
     * @param $mentorid int The ID of the mentor.
     * @param $experienceid int The ID of the experience.
     */
    public static function remove_mentor_request(int $mentorid, int $experienceid): bool
    {
        global $DB;
        return $DB->delete_records(self::$mentor_request_table, ['mentorid' => $mentorid, 'experienceid' => $experienceid]);
    }

    /**
     * Remove a mentor request.
     * 
     * @param $requestid int The ID of the mentor request.
     */
    public static function remove_mentor_request_by_id(int $requestid): bool
    {
        global $DB;
        return $DB->delete_records(self::$mentor_request_table, ['id' => $requestid]);
    }

}
