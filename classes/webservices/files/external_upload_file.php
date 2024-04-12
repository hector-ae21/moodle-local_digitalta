<?php
require_once($CFG->libdir . "/externallib.php");

class external_upload_file extends external_api
{

    public static function upload_file_parameters()
    {
        return new external_function_parameters(
            array(
                'itemid' => new external_value(PARAM_INT, 'Item ID of the file'),
                'filename' => new external_value(PARAM_TEXT, 'Name of the file'),
            )
        );
    }

    public static function upload_file($itemid, $filename)
    {
        global $USER;

        $context = context_user::instance($USER->id);
        $fs = get_file_storage();

        // Obtener el archivo del draft area
        $file = $fs->get_file(5, 'user', 'draft', $itemid, "/", $filename);
        if (!$file) {
            throw new moodle_exception('filenotfound', 'error');
        }
        file_save_draft_area_files(
            $itemid,
            5,
            'local_dta',
            'picture',
            12,
            [
                'subdirs' => 0,
                'maxfiles' => 1,
            ]
        );

        $files = $fs->get_area_files(
            5,
            'local_dta',
            'picture',
            12,
            'sortorder DESC, id ASC',
            false
        );

        $file = reset($files);
        $pictureurl = \moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );

        return array('status' => 'File saved successfully', 'url' => $pictureurl->out(false), 'files' => json_encode($files));
    }

    public static function upload_file_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_TEXT, 'Status message'),
                'url' => new external_value(PARAM_URL, 'URL of the uploaded file'),
                'files' => new external_value(PARAM_RAW, 'Files')
            )
        );
    }
}
