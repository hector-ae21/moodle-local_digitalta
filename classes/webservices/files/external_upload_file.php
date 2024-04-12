<?php
require_once($CFG->libdir . "/externallib.php");

class external_upload_file_from_draft extends external_api
{

    public static function upload_file_parameters()
    {
        return new external_function_parameters(
            array(
                'draftid' => new external_value(PARAM_INT, 'Draft area id', VALUE_REQUIRED),
                'fileid' => new external_value(PARAM_INT, 'File id', VALUE_REQUIRED),
                'filearea' => new external_value(PARAM_TEXT, 'File area', VALUE_REQUIRED),
                'contextid' => new external_value(PARAM_INT, 'Context id', VALUE_OPTIONAL, 1),
                'maxfiles' => new external_value(PARAM_INT, 'Max files', VALUE_OPTIONAL, 1),
                'maxbytes' => new external_value(PARAM_INT, 'Max bytes', VALUE_OPTIONAL, 0),
                'subdirs' => new external_value(PARAM_BOOL, 'Sub directories', VALUE_OPTIONAL, false)
            )
        );
    }

    public static function upload_file($contextid = 1, $draftid, $fileid, $filearea, $maxfiles = 1, $maxbytes = 0, $subdirs = false) {
        global $CFG, $USER;

        // Validate the context
        $context = context::instance_by_id($contextid);
        if (!$context) {
            throw new moodle_exception('invalidcontextid');
        }

        // Validate the draft area
        $draftitemid = file_get_submitted_draft_itemid($filearea);
        if ($draftitemid != $draftid) {
            throw new moodle_exception('draftmismatch');
        }

        file_save_draft_area_files(
            $draftid,
            $contextid,
            'local_dta',
            $filearea,
            $fileid,
            [
                'subdirs' => $subdirs,
                'maxfiles' => $maxfiles,
                'maxbytes' => $maxbytes
            ]
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
            return false;
        }

        $file = reset($files);
        $file_url = \moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );
        

        return array('status' => 'File saved successfully', 'url' => $file_url->out(false));
    }

    public static function upload_file_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_TEXT, 'Status message'),
                'url' => new external_value(PARAM_URL, 'URL of the uploaded file')
            )
        );
    }
}
