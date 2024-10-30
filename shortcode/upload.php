<?php

/**
 * Handle file uploads via XMLHttpRequest
 */

require_once( '../../../../wp-config.php' );

$wp->init();
$wp->parse_request();
$wp->query_posts();
$wp->register_globals();
if ( !current_user_can('edit_pages') ) die('Direct access to this script is forbidden.');

class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()){
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
    function validFile() {
        $filePath = "php://input";
        if (version_compare(PHP_VERSION, '5', '<'))
        {
            $fhandle = finfo_open(FILEINFO_MIME);
            $mime_type = finfo_file($fhandle,$filePath);
        }
        else
        {
            $file_info = new finfo(FILEINFO_MIME);
            $mime_type = $file_info->buffer(file_get_contents($filePath));
        }
        // $mime_type = '';
        if ( (strpos($mime_type,'image/jpeg') == 0) && (strpos($mime_type,'charset=binary') > 0) )
        {
            return true;
        }
        return false;
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
    function validFile() {
        $tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
        $filePath = $tmp_dir . '/' .$_FILES['qqfile']['tmp_name'];
        if (version_compare(PHP_VERSION, '5', '<'))
        {
            $fhandle = finfo_open(FILEINFO_MIME);
            $mime_type = finfo_file($fhandle,$filePath);
        }
        else
        {
            $file_info = new finfo(FILEINFO_MIME);
            $mime_type = $file_info->buffer(file_get_contents($filePath));
        }

        if ( (strpos($mime_type,'image/jpeg') == 0) && (strpos($mime_type,'charset=binary') > 0) )
        {
            return true;
        }
        return $filePath;
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        //$this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } /*elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } */else {
            $this->file = false;
        }
    }

    private function checkServerSettings(){
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('rr'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g':
                $val *= 1024;
                break;
            case 'm':
                $val *= 1024;
                break;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = false){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty');
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = str_replace(' ','_',$pathinfo['filename']);
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }

        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 400);
            }
        }
        $sec_rsp = $this->file->validFile();
        if (!$sec_rsp){

            return array('error' => 'Invalid file type');
        }

        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return array('success'=>true,'file_name'=>$filename);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }

    }
}

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array("jpg");
// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload('uploads/');
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
