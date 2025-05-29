<?php


function mismatch_user_service_upload_picture(
    $dbc,
    $user_id,
    $new_picture_name,
    $new_picture_type,
    $new_picture_size,
    $new_picture_tmp_name,
    &$old_picture_name
) {

    if (empty($new_picture_name)) {
        return null;
    }

    if (
        $new_picture_type != 'image/gif' &&
        $new_picture_type != 'image/jpeg' &&
        $new_picture_type != 'image/pjpeg' &&
        $new_picture_type != 'image/png'
    ) {
        @unlink($new_picture_tmp_name);
        return '<p class="error" >The new picture type must be a image.</p>';
    }

    if ($new_picture_size <= 0 || $new_picture_size > MM_MAXFILESIZE) {
        @unlink($new_picture_tmp_name);
        return '<p class="error"> The picture must be no  greater than ' .  (MM_MAXFILESIZE / 1024) . 'KB <p>';
    }

    list($new_picture_width, $new_picture_height) = getimagesize($new_picture_tmp_name);

    if ($new_picture_width > MM_MAXIMGWIDTH || $new_picture_height > MM_MAXIMGHEIGHT) {
        @unlink($new_picture_tmp_name);
        return '<p class="error"> The picture must be no greater than ' . (MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT) . ' pixels in size</p>';
    }

    $path  = MM_UPLOADPATH . basename($new_picture_name);

    $upload_result = move_uploaded_file($new_picture_tmp_name, $path);

    if ($upload_result == false) {
        @unlink($new_picture_tmp_name);
        return '<p class="error">Sorry, there was a problem uploading your picture</p>';
    }

    if ($old_picture_name != $new_picture_name) {
        @unlink(MM_UPLOADPATH . $old_picture_name);
    }

    $query = "UPDATE mismatch_user SET picture = '$new_picture_name' WHERE user_id = $user_id LIMIT 1";

    mysqli_query($dbc, $query) or die('error in mismatch_user_service_upload_picture');

    $old_picture_name = $new_picture_name;

    return '<p class="success">Your upload was sucessfuly</p>';
}
