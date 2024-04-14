<?php
/**
 * filemanager handler
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta\file;
require_once("$CFG->dirroot/lib/form/filemanager.php");

class filemanager_handler
{

    protected $options = [
        'maxfiles' => 1,
        'accepted_types' => array('.jpg', '.png', '.jpeg'),
        'maxbytes' => 5000000,
        'areamaxbytes' => 10485760,
        'return_types' => FILE_INTERNAL | FILE_EXTERNAL
    ];

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function init_filemanager($id = 'fileManager', $class = 'fileManager', $name = 'fileManager')
    {
        global $PAGE;

        $attributes = array('id' => $id, 'class' => $class, 'name' => $name);
        $filepicker = new \MoodleQuickForm_filemanager('filemanager', get_string('file'), $attributes, null, $this->options);
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
