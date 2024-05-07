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
 * File manager handler
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 // WARNING!!!!! DONT TOUCH THIS FILE

namespace local_dta\file;

require_once($CFG->dirroot . '/lib/form/filemanager.php');

/**
 * This class is used to handle files
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class FileManagerHandler
{
    private $draftitemid;

    /**
     * Default options for the filemanager
     * @var array $options
     * @access protected
     */
    protected $options = [
        'maxfiles' => 1,
        'subdirs' => 0,
        'accepted_types' => array('.jpg', '.png', '.jpeg'),
        'maxbytes' => 10485760,
        'areamaxbytes' => 10485760,
        'return_types' => FILE_INTERNAL | FILE_EXTERNAL,
    ];

    protected $attributes = [
        'id' => 'fileManager',
        'class' => 'fileManager',
        'name' => 'fileManager'
    ];

    public function __construct($draftitemid = null, $attributes = null, $options = null,)
    {
        $this->draftitemid = $draftitemid ?? rand(1, 999999999);
        if ($options) {
            $this->options = array_merge($this->options, $options);
        }
        if ($attributes) {
            $this->attributes = array_merge($this->attributes, $attributes);
        }
    }

    private function prepare_draft_area_with_files($filecontextid, $component, $filearea)
    {
        global $USER;

        $usercontext = \context_user::instance($USER->id);
        $fs = get_file_storage();

        $fs->delete_area_files($usercontext->id, 'user', 'draft', $this->draftitemid);

        if ($files = $fs->get_area_files($filecontextid, $component, $filearea, $this->draftitemid, 'sortorder DESC, id ASC', false)) {
            foreach ($files as $file) {
                if ($file->is_directory() and $file->get_filepath() === '/') {
                    continue;
                }
                $filerecord = array(
                    'contextid' => $usercontext->id,
                    'component' => 'user',
                    'filearea'  => 'draft',
                    'itemid'    => $this->draftitemid,
                    'filepath'  => $file->get_filepath(),
                    'filename'  => $file->get_filename(),
                );

                if (!$fs->file_exists($usercontext->id, 'user', 'draft', $this->draftitemid, $file->get_filepath(), $file->get_filename())) {
                    $fs->create_file_from_storedfile($filerecord, $file);
                }
            }
        }
    }

    public function init($filearea, $component = "local_dta", $filecontextid = 1)
    {
        global $PAGE;

        self::prepare_draft_area_with_files($filecontextid, $component, $filearea);
        $filepicker = new \MoodleQuickForm_filemanager('filemanager', get_string('file'), $this->attributes, $this->options);
        $filepicker->setValue($this->draftitemid);

        $html = json_encode($filepicker->toHtml());

        $inlinejs = <<<EOF
        M.custom = {};
        M.custom.filemanager = {
            html: $html
        };
        EOF;

        $PAGE->requires->js_amd_inline($inlinejs);
    }
}
