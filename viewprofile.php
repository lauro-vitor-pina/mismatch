<?php

require_once(__DIR__ . '/src/appvars.php');
require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__ . '/src/services/login/login_service_authenticate.php');
require_once(__DIR__ . '/src/services/mismatch_user/mismatch_user_service_get_by_id.php');

login_service_authenticate();

$user = null;

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (is_numeric($user_id)) {
  $dbc = connection_service_get_dbc();
  $user = mismatch_user_service_get_by_id($dbc, $user_id);
  connection_service_close($dbc);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Mismatch - View Profile</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>

  <h3>Mismatch - View Profile</h3>

  <?php if ($user != null) { ?>

    <table>
      <tr>
        <td class="label">First name:</td>
        <td><?php echo $user['first_name'] ?></td>
      </tr>
      <tr>
        <td class="label">Last name:</td>
        <td><?php echo $user['last_name'] ?></td>
      </tr>
      <tr>
        <td class="label">Gender:</td>
        <td><?php echo $user['gender'] ?></td>
      </tr>

      <tr>
        <td class="label">Birthdate:</td>
        <td> <?php echo $user['birthdate'];  ?></td>
      </tr>

      <tr>
        <td class="label">Location:</td>
        <td> <?php echo $user['city'] . ', ' . $user['state']; ?> </td>
      </tr>

      <?php if (!empty($user['picture'])) { ?>
        <tr>
          <td class="label">Picture:</td>
          <td>
            <img src="<?php echo MM_UPLOADPATH . $user['picture']; ?>" alt="Profile Picture"   width="80" height="80"/>
          </td>
        </tr>
      <?php } ?>

    </table>

  <?php  } else {
    echo '<p class="error">There was a problem accessing your profile.</p>';
  } ?>

</body>

</html>