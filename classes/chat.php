<?php

/**
 * Chat class
 *
 * @package   local_digitalta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_digitalta;

defined('MOODLE_INTERNAL') || die();

use stdClass;
use Exception;

class Chat
{

    private static $table_chat_room = 'digitalta_chat';
    private static $table_chat_messages = 'digitalta_chat_messages';
    private static $table_chat_users = 'digitalta_chat_users';

    private static $table_chat_read_status = 'digitalta_chat_read_status';


    /**
     * Create a chat room
     *
     * @param int $experience Experience level
     * @return stdClass
     */
    public static function create_chat_room($experience = 0)
    {
        global $DB;
        $chat_room = new stdClass();
        $chat_room->experienceid = $experience;
        $chat_room->timecreated = time();
        $chat_room->timemodified = time();
        $chat_room->id = $DB->insert_record(self::$table_chat_room, $chat_room);
        return $chat_room->id;
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
        $chatroom = $DB->get_record(self::$table_chat_room, array('id' => $id));
        $chatroom = $chatroom ? self::set_chat_names([$chatroom])[0] : null;
        return $chatroom;
    }

    /**
     * Get a chat room by experience
     *
     * @param int $experienceid Experience ID
     * @return stdClass|null
     */
    public static function get_chat_room_by_experience($experienceid): ?stdClass
    {
        global $DB;
        $chat = $DB->get_record(self::$table_chat_room, array('experienceid' => $experienceid));
        return $chat ? $chat : null;
    }

    /**
     * Add a user to a chat room
     *
     * @param int $chat_room_id Chat room ID
     * @param int $user_id User ID
     * @return stdClass
     */
    public static function chats_add_user_to_room($chat_room_id, $user_id): bool
    {
        global $DB;
        $chat_user = new stdClass();
        $chat_user->chatid = $chat_room_id;
        $chat_user->userid = $user_id;
        $chat_user->timecreated = time();
        $chat_user->timemodified = time();
        if (!$DB->insert_record(self::$table_chat_users, $chat_user)) {
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
        return $DB->get_records(self::$table_chat_users, array('chatid' => $chat_room_id));
    }

    /**
     * Add a message to a chat room
     *
     * @param int $chat_room_id Chat room ID
     * @param int $user_id User ID
     * @param string $message Message content
     * @return stdClass
     */
    public static function chats_add_message_to_chat_room($chat_room_id, $user_id, $message): bool
    {
        global $DB;
        if (!self::is_user_in_chat_room($chat_room_id, $user_id)) { // Check if user is in chat room (throws exception if not
            throw new Exception('User is not in chat room');
        }

        $chat_message = new stdClass();
        $chat_message->chatid = $chat_room_id;
        $chat_message->userid = $user_id;
        $chat_message->message = $message;
        $chat_message->timecreated = time();
        $chat_message->timemodified = time();
        $chat_message->id = $DB->insert_record(self::$table_chat_messages, $chat_message);
        if (!$chat_message->id) {
            throw new Exception('Error inserting chat message');
        }
        return true;
    }

    /**
     * Get messages in a chat room
     *
     * @param int $chat_room_id Chat room ID
     * @param int|null $limit Limit of messages to retrieve
     * @param int $offset Offset for pagination
     * @return array
     */
    public static function get_chat_messages(int $chat_room_id, int $userid = 0, ?int $limit = null, int $offset = 0): array
    {
        global $DB, $USER, $PAGE;

        $PAGE->set_context(\context_system::instance());

        if ($userid == 0) {
            $userid = $USER->id;
        }

        if (!self::is_user_in_chat_room($chat_room_id, $userid)) {
            throw new Exception('User is not in chat room');
        }

        $sql = "SELECT * FROM {" . self::$table_chat_messages . "} WHERE chatid = ?";
        if (!is_null($limit)) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        $messages = array_values($DB->get_records_sql($sql, array($chat_room_id)));
        $messages = array_map(function ($message) use ($DB, $PAGE) {
            $userinfo = $DB->get_record('user', array('id' => $message->userid));
            $message->userfullname = $userinfo->firstname . ' ' . $userinfo->lastname;
            $userpicture = new \user_picture($userinfo);
            $userpicture->size = 100;  // Tamaño de la imagen en píxeles (puedes cambiar esto)
            $message->userpicture = $userpicture->get_url($PAGE)->__toString();
            return (object) $message;
        }, $messages);

        return self::prepare_messages_output($messages);
    }

    /**
     * Prepare messages output
     * @param array $messages Messages as it comes from the database
     * @return array
     */
    public static function prepare_messages_output($messages): array
    {
        global $USER;
        $output = [];
        foreach ($messages as $message) {
            $is_mine = $message->userid == $USER->id ? true : false;
            $output[] = [
                'id' => $message->id,
                'chatid' => $message->chatid,
                'userid' => $message->userid,
                'message' => $message->message,
                'timecreated' => $message->timecreated,
                'timemodified' => $message->timemodified,
                'is_mine' => $is_mine,
                'userfullname' => $message->userfullname,
                'userpicture' => $message->userpicture
            ];
        }
        return $output;
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
        return $DB->record_exists(self::$table_chat_users, array('chatid' => $chat_room_id, 'userid' => $user_id));
    }

    /**
     * Get all chat rooms for a user
     *
     * @param int $userid User ID
     * @param int $experienceid Experience ID
     * @return array
     */
    public static function chats_get_rooms($userid = null, $experienceid = null): array
    {
        global $DB, $USER;

        if (is_null($userid)) {
            $userid = $USER->id;
        }

        $sql = "SELECT
        cr.*, MAX(cm.timecreated) as last_message_time
        FROM
             {digitalta_chat_users}  cu
        INNER JOIN  {digitalta_chat}  cr
        ON
            cu.chatid = cr.id
        LEFT JOIN {digitalta_chat_messages} cm
        ON cr.id = cm.chatid
        WHERE
            cu.userid = ?
        ";

        if ($experienceid) {
            $sql .= " AND cr.experienceid = ? ";
        }

        $sql .= "GROUP BY cr.id ORDER BY last_message_time DESC";

        $params = [$userid];
        if ($experienceid) {
            $params[] = $experienceid;
        }

        $chat_rooms = array_values($DB->get_records_sql($sql, $params));
        $chat_rooms = self::set_chat_names($chat_rooms, $userid);
        $chat_rooms = self::set_unread_messages($chat_rooms, $userid);

        return $chat_rooms;
    }

    /**
     * Set chat names
     */
    public static function set_chat_names($chat_rooms, $userid = null): array
    {
        global $DB, $PAGE;

        $PAGE->set_context(\context_system::instance());
        $chat_rooms_with_names = [];
        foreach ($chat_rooms as $chat_room) {

            $chat_users = $DB->get_records(self::$table_chat_users, array('chatid' => $chat_room->id));
            $chat_room->users = [];
            foreach ($chat_users as $chat_user) {
                $userdb = $DB->get_record('user', array('id' => $chat_user->userid));
                $userinfo = new stdClass();
                $userinfo->firstname = $userdb->firstname;
                $userinfo->lastname = $userdb->lastname;
                $userinfo->userid = $userdb->id;
                array_push($chat_room->users, $userinfo);
            }

            $chat_room->name = '';
            if ($chat_room->experienceid) {
                $experience = $DB->get_record('digitalta_experiences', array('id' => $chat_room->experienceid));
                $chat_room->name = $experience->title;
                $chat_room->ownexperience = $experience->userid == $userid;
            }
            $chat_rooms_with_names[] = $chat_room;
        }
        return $chat_rooms_with_names;
    }

    public static function set_unread_messages($chat_rooms, $userid)
    {
        global $DB;
        $time_limit = time() - (14 * 24 * 60 * 60); // 14 days ago

        foreach ($chat_rooms as $chat_room) {
            $chat_room->unread_messages = 0;

            $query = "SELECT COUNT(*) AS unread_count
                FROM {digitalta_chat_messages} m
                LEFT JOIN {digitalta_chat_read_status} r
                    ON m.id = r.messageid AND r.userid = :userid
                INNER JOIN {digitalta_chat} c
                    ON m.chatid = c.id
                INNER JOIN {digitalta_experiences} e
                    ON c.experienceid = e.id
                WHERE r.id IS NULL
                    AND m.chatid = :chatid
                    AND m.userid != :userid1
                    AND m.timecreated >= :time_limit
                GROUP BY m.chatid";

            $params = [
                'userid' => $userid,
                'chatid' => $chat_room->id,
                'userid1' => $userid,
                'time_limit' => $time_limit
            ];

            $unread_count = $DB->get_field_sql($query, $params);
            $chat_room->unread_messages = $unread_count ? $unread_count : 0;
        }

        return $chat_rooms;
    }


    /**
     * Set chat names
     */
    public static function delete_chat_with_experience($experienceid): void
    {
        global $DB;
        $chat = $DB->get_record(self::$table_chat_room, array('experienceid' => $experienceid));
        if ($chat) {
            $DB->delete_records(self::$table_chat_users, array('chatid' => $chat->id));
            $DB->delete_records(self::$table_chat_messages, array('chatid' => $chat->id));
            $DB->delete_records(self::$table_chat_room, array('experienceid' => $experienceid));
        }
    }

    public static function get_unread_chatrooms($userid = null)
    {
        global $DB, $USER;

        if (is_null($userid)) {
            $userid = $USER->id;
        }

        $time_limit = time() - (14 * 24 * 60 * 60); // 14 days ago

        $sql = "SELECT COUNT(DISTINCT m.chatid) AS unread_chats
                FROM {digitalta_chat_messages} m
                LEFT JOIN {digitalta_chat_read_status} r
                    ON m.id = r.messageid AND r.userid = :userid
                INNER JOIN {digitalta_chat} c
                    ON m.chatid = c.id
                INNER JOIN {digitalta_experiences} e
                    ON c.experienceid = e.id
                WHERE r.id IS NULL 
                    AND m.userid != :userid1 
                    AND m.timecreated >= :time_limit";

        $params = [
            'userid' => $userid,
            'userid1' => $userid,
            'time_limit' => $time_limit
        ];

        return $DB->get_field_sql($sql, $params) ?? 0;
    }

    public static function mark_messages_as_read($chatid, $userid, $messageids = null)
    {
        try {
            global $DB;
            if ($messageids === null || empty($messageids) || count($messageids) === 0) {
                $messages = $DB->get_records(self::$table_chat_messages, ['chatid' => $chatid]);
            } else {
                list($in_sql, $params) = $DB->get_in_or_equal($messageids, \SQL_PARAMS_NAMED);
                $params['chatid'] = $chatid;
                $messages = $DB->get_records_select(self::$table_chat_messages, "chatid = :chatid AND id $in_sql", $params);
            }
            foreach ($messages as $message) {
                if ($message->userid != $userid) {
                    $exists = $DB->record_exists(self::$table_chat_read_status, ['messageid' => $message->id, 'userid' => $userid]);
                    if (!$exists) {
                        $read_status = new stdClass();
                        $read_status->messageid = $message->id;
                        $read_status->userid = $userid;
                        $read_status->read_at = time();
                        $DB->insert_record(self::$table_chat_read_status, $read_status);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception('Error marking messages as read');
        }

        return true;
    }
}
