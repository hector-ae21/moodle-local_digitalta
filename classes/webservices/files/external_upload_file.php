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

        // Prepare file record for the target file area
        $file_record = array(
            'contextid' => context_system::instance()->id, // or another context id depending on your requirements
            'component' => 'local_dta',
            'filearea'  => 'picture', // Change as per your file area definition
            'itemid'    => $itemid,
            'filepath'  => "/",
            'filename'  => $filename,
        );

        // Move the file from draft to the final file area
        $newfile = $fs->create_file_from_storedfile($file_record, $file);

        // Return the URL of the file
        $url = moodle_url::make_pluginfile_url($newfile->get_contextid(), $newfile->get_component(),
                                               $newfile->get_filearea(), $newfile->get_itemid(),
                                               $newfile->get_filepath(), $newfile->get_filename());

        return array('status' => 'File saved successfully', 'url' => $url->out(false));
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
