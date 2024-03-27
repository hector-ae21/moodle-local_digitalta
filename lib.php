<?php

/**
 *  lib file for the local experiences plugin
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../../config.php');



/**
 * check permissions to delete or edit an experience owner or admin
 * 
 */
function local_dta_check_permissions($experience, $user)
{
    global $USER;
    
    if ($user->id == $experience->userid || is_siteadmin($USER)) {
        return true;
    }
    return false;
}

/**
 * check permissions to delete or edit a case owner or admin
 * 
 */
function local_dta_check_permissions_case($case, $user)
{
    global $USER;
    
    if ($user->id == $case->userid  || is_siteadmin($USER)) {
        return true;
    }
    return false;
}

/**
 * Serve the files from the myplugin file areas.
 *
 * @param stdClass $course The course object
 * @param stdClass $cm The course module object
 * @param stdClass $context The context
 * @param string $filearea The name of the file area
 * @param array $args Extra arguments (itemid, path)
 * @param bool $forcedownload Whether or not force download
 * @param array $options Additional options affecting the file serving
 * @return bool false If the file not found, just send the file otherwise and do not return anything
 */
function local_dta_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {

    if ($context->contextlevel !== CONTEXT_SYSTEM) {
        return false;
    }

    $itemid = array_shift($args);
    $filename = array_pop($args);
    $filepath = (empty($args)) ? '/' : '/' . implode('/', $args) . '/';

    $fs = get_file_storage();
    if (!$file = $fs->get_file($context->id, 'local_dta', $filearea, $itemid, $filepath, $filename)) {
        return false;
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}



class CONSTANTS{
    const GROUPS = [
        "WHAT" => 1 ,
        "SO_WHAT" => 2,
        "NOW_WHAT" => 3,
        "EXTRA" => 4 
    ]; 

    const SECTION_TYPES = [
        "TEXT" => [
            "ID" => 1,
            "TABLE" => "digital_refl_sec_text",
        ]
    ];
}