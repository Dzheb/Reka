<?
function img_save()
{
    // сохранение фото
    if (!empty($_FILES)) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/images/';
        $filename = $_FILES['file']['name'];
        $filename_name = basename($filename);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $filename_name = $path . $filename_name;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $filename_name)) {
            return true;
        }
    }
    return false;
}

function img_delete()
{

    // удаление фото офиса компании
    if (!empty($_POST['file'])) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/images/';
        $filename = $_POST['file'];
        if (file_exists($path . $filename)) {
            unlink($path . $filename);
        }
        return true;
        //delete from DB
        // mysqli_query($dbcon,"DELETE FROM company_photo WHERE  photo_office = '".$filename."'");
    }
    return false;
}
