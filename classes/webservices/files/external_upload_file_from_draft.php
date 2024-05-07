<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * WebService to upload a file from draft area
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir . "/externallib.php");
require_once($CFG->libdir . "/filelib.php");

/**
 * This class is used to upload a file from draft area
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_upload_file_from_draft extends external_api
{

    protected static $_options = [
        'maxfiles' => 1,
        'maxbytes' => 0,
        'subdirs' => false
    ];

    public static function upload_file_from_draft_parameters()
    {
        return new external_function_parameters(
            array(
                'draftid' => new external_value(PARAM_INT, 'Draft area id', VALUE_REQUIRED),
                'fileid' => new external_value(PARAM_INT, 'File id', VALUE_REQUIRED),
                'filearea' => new external_value(PARAM_TEXT, 'File area', VALUE_REQUIRED),
                'contextid' => new external_value(PARAM_INT, 'Context id', VALUE_DEFAULT, 1),
                'options' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'maxfiles' => new external_value(PARAM_INT, 'Max files', VALUE_DEFAULT, 1),
                            'maxbytes' => new external_value(PARAM_INT, 'Max bytes', VALUE_DEFAULT, 0),
                            'subdirs' => new external_value(PARAM_BOOL, 'Sub directories', VALUE_DEFAULT, false)
                        )
                    ),
                    'Options for file upload',
                    VALUE_DEFAULT,
                    []
                )
            )
        );
    }

    protected static function set_options($options)
    {
        self::$_options = array_merge(self::$_options, $options);
    }

    public static function upload_file_from_draft($draftid, $fileid, $filearea, $contextid = 1, $options = [])
    {
        self::set_options($options);

        file_save_draft_area_files(
            $draftid,
            $contextid,
            'local_dta',
            $filearea,
            $fileid,
            self::$_options
        );

        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $contextid,
            'local_dta',
            $filearea,
            $fileid,
            'sortorder DESC, id ASC',
            false
        );

        if (empty($files)) {
            return ['status' => 'Error uploading file'];
        }

        $file = reset($files);
        $url = moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );

        return ['status' => 'File uploaded', 'url' => $url->out()];
    }

    public static function upload_file_from_draft_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_TEXT, 'Status message'),
                'url' => new external_value(PARAM_URL, 'URL of the uploaded file', VALUE_OPTIONAL)
            )
        );
    }
}
