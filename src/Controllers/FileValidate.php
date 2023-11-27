<?php 

declare(strict_types=1);

class FileValidate{

    public function checkCsvFile($file){
        $types = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        $_SESSION['file_send']['error'] = true;
        $_SESSION['file_send']['created'] = time();

        if($file['fileToUpload']['error']){
            $_SESSION['file_send']['result'] = "Sorry, there was some error, can you try again?";
        }
        // check if type of file is csv
        if(!in_array($file['fileToUpload']['type'],$types)){
            $_SESSION['file_send']['result'] = "Sorry, file type not allowed";
            die("Sorry, file type not allowed");
        }
        // size should be less than 1mb
        if($file['fileToUpload']['size'] > 1048576){
            $_SESSION['file_send']['result'] = 'Sorry, but file size is too big';
            die('Sorry, but file size is too big');
        }
        $tmp_name = substr($file['fileToUpload']['tmp_name'], 4);

        move_uploaded_file($tmp_name, "../fileStorage/" . $tmp_name . '.csv');

        $_SESSION['file_send']['error'] = false;
        $_SESSION['file_send']['result'] = 'File was send succesfully';
    }
}