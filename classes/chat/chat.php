<?php

/**
 * Chat class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

defined('MOODLE_INTERNAL') || die();

use stdClass;

class Chat
{

    private static $table_chat_room = 'digital_chat';
    private static $table_chat_messages = 'digital_chat_messages';
    private static $table_chat_users = 'digital_chat_users';


    /**
     * Create a chat room
     *
     * @param int $experience Experience level
     * @return stdClass
     */
    public static function create_chat_room($experience = 0): stdClass
    {
        global $DB;
        $chat_room = new stdClass();
        $chat_room->experience = $experience;
        $chat_room->timecreated = date('Y-m-d H:i:s', time());
        $chat_room->timemodified = date('Y-m-d H:i:s', time());
        $chat_room->id = $DB->insert_record(self::$table_chat_room, $chat_room);
        return $chat_room;
    }

    /**
     * Get a chat room by ID
     *
     * @param int $id Chat room ID
     * @return stdClass|null
     */
    public static function get_chat_room($id): ?stdClass
    {
        global $DB;
        return $DB->get_record(self::$table_chat_room, array('id' => $id));
    }

    /**
     * Add a user to a chat room
     *
     * @param int $chat_room_id Chat room ID
     * @param int $user_id User ID
     * @return stdClass
     */
    public static function add_user_to_chat_room($chat_room_id, $user_id): bool
    {
        global $DB;
        $chat_user = new stdClass();
        $chat_user->chat_room_id = $chat_room_id;
        $chat_user->user_id = $user_id;
        $chat_user->timecreated = date('Y-m-d H:i:s', time());
        $chat_user->timemodified = date('Y-m-d H:i:s', time());
        if(!$chat_user->id = $DB->insert_record(self::$table_chat_users, $chat_user)){
            return false;
        }
        return true;
    }

    /**
     * Get users in a chat room
     *
     * @param int $chat_room_id Chat room ID
     * @return array
     */
    public static function get_chat_users($chat_room_id): array
    {
        global $DB;
        return $DB->get_records(self::$table_chat_users, array('chat_room_id' => $chat_room_id));
    }

    /**
     * Add a message to a chat room
     *
     * @param int $chat_room_id Chat room ID
     * @param int $user_id User ID
     * @param string $message Message content
     * @return stdClass
     */
    public static function add_message_to_chat_room($chat_room_id, $user_id, $message): stdClass
    {
        global $DB;
        $chat_message = new stdClass();
        $chat_message->chat_room_id = $chat_room_id;
        $chat_message->user_id = $user_id;
        $chat_message->message = $message;
        $chat_message->timecreated = date('Y-m-d H:i:s', time());
        $chat_message->timemodified = date('Y-m-d H:i:s', time());
        $chat_message->id = $DB->insert_record(self::$table_chat_messages, $chat_message);
        return $chat_message;
    }

    /**
     * Get messages in a chat room
     *
     * @param int $chat_room_id Chat room ID
     * @param int|null $limit Limit of messages to retrieve
     * @param int $offset Offset for pagination
     * @return array
     */
    public static function get_chat_messages($chat_room_id, ?int $limit = null, int $offset = 0): array
    {
        global $DB, $USER;

        if (!self::is_user_in_chat_room($chat_room_id, $USER->id)) {
            throw new Exception('User is not in chat room');
        }

        $sql = "SELECT * FROM {" . self::$table_chat_messages . "} WHERE chat_room_id = ?";
        if (!is_null($limit)) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }
        return $DB->get_records_sql($sql, array($chat_room_id));
    }

    /**
     * Get messages in a chat room by user
     *
     * @param int $chat_room_id Chat room ID
     * @param int $user_id User ID
     * @return array
     */
    public static function get_chat_messages_by_user($chat_room_id, $user_id): array
    {
        global $DB;
        return $DB->get_records(self::$table_chat_messages, array('chat_room_id' => $chat_room_id, 'user_id' => $user_id));
    }

    /**
     * Check if a user is in a chat room
     *
     * @param int $chat_room_id Chat room ID
     * @param int $user_id User ID
     * @return bool
     */
    public static function is_user_in_chat_room($chat_room_id, $user_id): bool
    {
        global $DB;
        return $DB->record_exists(self::$table_chat_users, array('chat_room_id' => $chat_room_id, 'user_id' => $user_id));
    }

    /**
     * Get all chat rooms for a user
     *
     * @return array
     */
    public static function get_chat_rooms($userid = null): array
    {
        global $DB, $USER;

        $user_id = $userid ?? $USER->id;

        $sql = "SELECT
        *
        FROM
             {digital_chat_users}  cu
        INNER JOIN  {digital_chat}  cr
        ON
            cu.chatid = cr.id
        WHERE
        cu.userid = 2;";

        $chat_rooms = array_values($DB->get_records_sql($sql, array($user_id)));
        
        return $chat_rooms;
    }

}
