<?php

/**
 * Themes class.
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This class is used to manage the themes of the plugin

namespace local_dta;

defined('MOODLE_INTERNAL') || die();

use Exception;

/**
 * Class theme_context
 *
 * This class handles operations related to theme contexts.
 */
class Themes
{
    /** @var string The name of the database table storing the themes. */
    private static $table = 'digital_themes';

    /**
     * Get a theme by its ID.
     *
     * @param int $theme_id The ID of the theme.
     * @return object The retrieved theme object.
     */
    public static function get_theme(int $theme_id): object
    {
        global $DB;

        $theme = $DB->get_record(self::$table, array('id' => $theme_id));

        return $theme;
    }

    /**
     * Get all themes.
     *
     * @return array An array containing all themes.
     */
    public static function get_themes(): array
    {
        global $DB;

        $themes = $DB->get_records(self::$table);

        return $themes;
    }

    /**
     * Check if a theme object is valid.
     *
     * @param object $theme The theme object to validate.
     * @return bool True if the theme is valid, false otherwise.
     */
    public static function is_valid(object $theme): bool
    {
        if (empty($theme->name)) {
            return false;
        }   
        return true;
    }

    /**
     * Add a new theme.
     *
     * @param object $theme The theme object to add.
     * @return int The ID of the added theme.
     * @throws Exception If the theme is invalid.
     */
    public static function add_theme(object $theme): int
    {
        global $DB;

        if (!self::is_valid($theme)) {
            throw new Exception('Invalid theme');
        }

        $theme_id = $DB->insert_record(self::$table, $theme);

        return $theme_id;
    }

    /**
     * Update an existing theme.
     *
     * @param object $theme The theme object to update.
     * @throws Exception If the theme is invalid.
     */
    public static function update_theme(object $theme): void
    {
        global $DB;

        if (!self::is_valid($theme)) {
            throw new Exception('Invalid theme');
        }

        $DB->update_record(self::$table, $theme);
    }

    /**
     * Insert or update a theme.
     *
     * @param object $theme The theme object to upsert.
     * @return object The upserted theme object.
     * @throws Exception If the theme is invalid.
     */
    public static function upsert_theme(object $theme): object
    {
        global $DB;

        if (!self::is_valid($theme)) {
            throw new Exception('Invalid theme');
        }

        if (isset($theme->id)) {
            $DB->update_record(self::$table, $theme);
        } else {
            $theme->id = $DB->insert_record(self::$table, $theme);
        }

        return $theme;
    }

    /**
     * Delete a theme by its ID.
     *
     * @param int $theme_id The ID of the theme to delete.
     */
    public static function delete_theme(int $theme_id): void
    {
        global $DB;

        $DB->delete_records(self::$table, array('id' => $theme_id));
    }
}
/**
 * Class theme_context
 *
 * This class handles operations related to theme contexts.
 */
class theme_context
{
    /** @var string The name of the database table storing the themes context. */
    private static $table_context = 'digital_themes_context';

    /** @var array Possible types of context */
    private static $types = CONSTANTS::THEMES_CONTEXT;

    /**
     * Get a theme context by its type and instance.
     *
     * @param int $type The type of the theme context.
     * @param int $instance The instance of the theme context.
     * @return object The retrieved theme context object.
     */
    public static function get_theme_context(int $type, int $instance): object
    {
        global $DB;

        if (!self::is_valid_type($type)) {
            throw new Exception('Invalid theme context, invalid type');
        }

        $theme_context = $DB->get_record(self::$table_context, array('type' => $type, 'instance' => $instance));

        return $theme_context;
    }


    /**
     * Adds a theme context to the database.
     *
     * @param object $theme_context The theme context object to be added.
     * @return int The ID of the inserted theme context.
     * @throws Exception When the theme context is invalid.
     */
    public static function add_theme_context(object $theme_context): int
    {
        global $DB;

        if (!self::is_valid_context($theme_context)) {
            throw new Exception('Invalid theme context');
        }

        $theme_context_id = $DB->insert_record(self::$table_context, $theme_context);

        return $theme_context_id;
    }

    /**
     * Updates a theme context in the database.
     *
     * @param object $theme_context The theme context object to be updated.
     * @throws Exception When the theme context is invalid.
     */
    public static function update_theme_context(object $theme_context): void
    {
        global $DB;

        if (!self::is_valid_context($theme_context)) {
            throw new Exception('Invalid theme context');
        }

        $DB->update_record(self::$table_context, $theme_context);
    }

    /**
     * Deletes a theme context from the database.
     *
     * @param int $theme_context_id The ID of the theme context to be deleted.
     */
    public static function delete_theme_context(int $theme_context_id): void
    {
        global $DB;

        $DB->delete_records(self::$table_context, array('id' => $theme_context_id));
    }

    /**
     * Checks if a theme context is valid.
     *
     * @param object $theme_context The theme context object to be validated.
     * @return bool True if the theme context is valid, false otherwise.
     * @throws Exception When the theme context is invalid.
     */
    private static function is_valid_context(object $theme_context): bool
    {
        if (empty($theme_context->type) || empty($theme_context->instance) || empty($theme_context->theme)) {
            throw new Exception('Invalid theme context, missing fields');
        }

        if (!self::is_valid_type($theme_context->type)) {
            throw new Exception('Invalid theme context, invalid type');
        }

        return true;
    }

    /**
     * Checks if a given type is valid.
     *
     * @param int $type The type to be validated.
     * @return bool True if the type is valid, false otherwise.
     */
    private static function is_valid_type(int $type): bool
    {
        return in_array($type, self::$types);
    }
}
