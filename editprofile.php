<?php
require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__  . '/src/services/login/login_service_authenticate.php');
require_once(__DIR__ . '/src/services/login/login_service_get_user_logged.php');
require_once(__DIR__ . '/src/services/mismatch_user/mismatch_user_service_get_by_id.php');
require_once(__DIR__ . '/src/services/mismatch_user/mismatch_user_service_update.php');
require_once(__DIR__ . '/src/services/mismatch_user/mismatch_user_service_upload_picture.php');
require_once(__DIR__ . '/src/services/mismatch_user/mismatch_user_service_validate_update.php');

login_service_authenticate();

$user_logged = login_service_get_user_logged();

$dbc = connection_service_get_dbc();

if (isset($_POST['submit'])) {

  $first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
  $last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
  $gender = mysqli_real_escape_string($dbc, trim($_POST['gender']));
  $birthdate = mysqli_real_escape_string($dbc, trim($_POST['birthdate']));
  $city = mysqli_real_escape_string($dbc, trim($_POST['city']));
  $state = mysqli_real_escape_string($dbc, trim($_POST['state']));
  $old_picture = mysqli_real_escape_string($dbc, trim($_POST['old_picture']));
  $new_picture_tmp_name = $_FILES['new_picture']['tmp_name'];
  $new_picture_name = $_FILES['new_picture']['name'];
  $new_picture_type = $_FILES['new_picture']['type'];
  $new_picture_size = $_FILES['new_picture']['size'];
  $result_validate_update = '';
  $result_update = '';
  $result_upload_picture = '';

  $result_validate_update = mismatch_user_service_validate_update(
    $user_logged['id'],
    $first_name,
    $last_name,
    $gender,
    $birthdate,
    $city,
    $state
  );

  if ($result_validate_update == null) {

    $result_update = mismatch_user_service_update(
      $dbc,
      $user_logged['id'],
      $first_name,
      $last_name,
      $gender,
      $birthdate,
      $city,
      $state
    );

    $result_upload_picture = mismatch_user_service_upload_picture(
      $dbc,
      $user_logged['id'],
      $new_picture_name,
      $new_picture_type,
      $new_picture_size,
      $new_picture_tmp_name,
      $old_picture
    );
  }
} else {

  $result_get_by_id = mismatch_user_service_get_by_id($dbc, $user_logged['id']);

  $first_name = $result_get_by_id['first_name'];
  $last_name = $result_get_by_id['last_name'];
  $gender = $result_get_by_id['gender'];
  $birthdate = $result_get_by_id['birthdate'];
  $city = $result_get_by_id['city'];
  $state = $result_get_by_id['state'];
  $old_picture = $result_get_by_id['picture'];
}

connection_service_close($dbc);
?>

<?php
$page_title = 'Edit Profile';
require_once(__DIR__ . '/src/templates/header.php');
require_once(__DIR__ . '/src/templates/navmenu.php');
?>

<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

  <input type="hidden" name="old_picture" value="<?php echo $old_picture; ?>" />

  <fieldset>

    <legend>Personal Information</legend>

    <div>
      <label for="first_name">First name: <span class="error">*</span></label>
      <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" />
    </div>
    <br />

    <div>
      <label for="last_name">Last name: <span class="error">*</span></label>
      <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" />
    </div>
    <br />

    <div>
      <label for="gender">Gender: <span class="error">*</span></label>
      <select id="gender" name="gender">
        <option value="M" <?php if ($gender == 'M') echo 'selected = "selected"'; ?>>Male</option>
        <option value="F" <?php if ($gender == 'F') echo 'selected = "selected"'; ?>>Female</option>
      </select>
    </div>
    <br />

    <div>
      <label for="birthdate">Birthdate: <span class="error">*</span></label>
      <input type="date" id="birthdate" name="birthdate" value="<?php echo $birthdate; ?>" />
    </div>
    <br />

    <div>
      <label for="city">City: <span class="error">*</span></label>
      <input type="text" id="city" name="city" value="<?php echo $city; ?>" />
    </div>
    <br />

    <div>
      <label for="state">State: <span class="error">*</span></label>
      <input type="text" id="state" name="state" minlength="0" maxlength="2" value="<?php echo $state; ?>" />
    </div>
    <br />


    <div style="display:flex">
      <div>
        <label for="new_picture">Picture:</label>
        <input type="file" id="new_picture" name="new_picture" />
      </div>

      <div>

        <?php if (!empty($old_picture)) { ?>
          <img class="profile" src="<?php echo MM_UPLOADPATH . $old_picture; ?>" alt="Profile Picture" width="100" height="auto" />
        <?php  } ?>

      </div>

    </div>

  </fieldset>
  <br>
  <input type="submit" value="Save Profile" name="submit" />
  <br>

  <?php

  if (isset($result_update)) echo $result_update;

  if (isset($result_upload_picture)) echo $result_upload_picture;

  if (isset($result_validate_update) && $result_validate_update != null) {

    foreach ($result_validate_update as $item)
      echo $item;
  }

  ?>

</form>

<?php require_once(__DIR__ . '/src/templates/footer.php'); ?>