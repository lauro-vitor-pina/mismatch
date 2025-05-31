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

<?php
$page_title = 'View Profile';
require_once(__DIR__ . '/src/templates/header.php');
require_once(__DIR__ . '/src/templates/navmenu.php');
?>

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
          <img src="<?php echo MM_UPLOADPATH . $user['picture']; ?>" alt="Profile Picture" width="80" height="80" />
        </td>
      </tr>
    <?php } ?>

  </table>

<?php } else echo '<p class="error">There was a problem accessing your profile.</p>'; ?>

<?php require_once(__DIR__ . '/src/templates/footer.php'); ?>