<?php include 'header.php';

//keep pages private from non-registered users
if (!isset($_SESSION['userid'])) {
  header('Location: ./login.php');
}

if (isset($_POST['submit'])) {

  $id = $_SESSION['id'];

  // Logic to handle what is updated, since not everything may be changed at one time.
  // Password Change
  if ($_POST['password']) {
    if (htmlspecialchars($_POST['password']) === htmlspecialchars($_POST['password-conf'])) {
      $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $mysqli->execute_query("UPDATE `users` SET  password = '$hashed' WHERE `users`.`id`=?;", [$id]);
      $_SESSION['password'] = $hashed;
    } else {
      echo 'passwords to not match';
    }
  }
  // Location Change
  if ($_POST['location']) {
    $loc = htmlspecialchars($_POST['location']);
    $mysqli->execute_query("UPDATE `users` SET location = '$loc' WHERE id=?", [$id]);
    $_SESSION["loc"] = $loc;
  }
  // Phone Number Change
  if ($_POST['phone']) {
    $phone = htmlspecialchars($_POST['phone']);
    $mysqli->execute_query("UPDATE `users` SET phone = '$phone' WHERE id=?", [$id]);
    $_SESSION["phone"] = $phone;
  }
  // File upload 
  if (isset($_FILES['upload']['name'])) {
    $uploadOk = 1;
    $path = "./assets/";
    $tmp_name = $_FILES['upload']['tmp_name'];
    $name = $_FILES['upload']['name'];
    $hash = md5($name);

    $filetype = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $file_path = $path . $hash . '.' . $filetype;
    $filename = $hash . '.' . $filetype;

    if ($_FILES['upload']['size'] > 100000) {
      array_push($_SESSION['errors'], "Filesize too large");
      echo "Filesize too large, uploads must be under 10mb";
      $uploadOk = 0;
    }

    if ($filetype != "jpg" && $filetype != "png" && $filetype != "jpeg" && $filetype != "bmp" && $filetype != "svg") {
      array_push($_SESSION['errors'], "Unsupported filetype");
      echo "We only accept files in format: JPG, PNG, JPEG, BMP or SVG.";
      $uploadOk = 0;
    }

    if ($uploadOk = 0) {
      echo "There was an error(s) with your upload.";
      $_SESSION['errors'] = $errors;
      // header('Location: ./settings.php');
    } else {
      $_SESSION['errors'] = [];
      if (move_uploaded_file($tmp_name, $file_path)) {
        $mysqli->execute_query("UPDATE `users` SET photo = ? WHERE id=?", [$filename, $id]);
        $_SESSION['photo'] = $file_path;
        echo 'Successfully uploaded new photo!';
      }
    }
    if ((isset($_SESSION['type']) && $_SESSION['type'] === 'client')) {
      if (!is_null($_POST['type'])) {
        $type = htmlspecialchars($_POST['type']);
      }
      if (!is_null($_POST['ind'])) {
        $ind = htmlspecialchars($_POST['ind']);
      }
      if (!is_null($_POST['country'])) {
        $country = htmlspecialchars($_POST['country']);
      }
      if (!is_null($_POST['risk'])) {
        $risk = htmlspecialchars($_POST['risk']);
      }


      $sql = "INSERT INTO 'client-preferences' ('client_id', 'type', 'ind', 'country', 'risk' )
      VALUES ('$id', '$type','$country', '$ind', '$risk')";

      if ($mysqli->execute_query($sql)) {
        echo '<script type="text/javascript">alert("success");</script>';
      } else {
        echo '<script type="text/javascript">alert("failure");</script>';
      }
    }
  }
}
?>

<div class="settings-page">
  <?php if (isset($_SESSION['errors'])) {
    for ($i = 0; $i < count($_SESSION['errors']); $i++) {
      echo $_SESSION['errors'][$i] . '</br>';
    }
  } ?>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="settings-form" enctype="multipart/form-data">
    <div class="image-upload">
      <label for="upload">Choose a file to upload: </label>
      <input type="file" name="upload" id="upload">
    </div>
    <div class="loc" id="settings-location">
      <label for="location">Location: </label>
      <input type="text" name="location" id="location" placeholder="London, UK">
    </div>
    <div class="phone">
      <label for="phone">Phone: </label>
      <input type="text" name="phone" id="phone" placeholder="Include country code">
    </div>
    <div class="password">
      <label for="password">Password: </label>
      <input type="password" name="password" id="password">
    </div>
    <div class="password-conf">
      <label for="password-conf">Confirm Password: </label>
      <input type="password" name="password-conf" id="password-conf">
    </div>
    <?php if ($_SESSION['type'] === 'client') : ?>

      <div class="preferences-form-area" id="preferences-type">
        <label for="type">Asset Type: </label>
        <input class="preference-text" type="text" name="type" placeholder=""> <br>
      </div>
      <div class="preferences-form-area" id="preferences-ind">
        <label for="ind1">Industry Sector: </label>
        <input class="preference-text" type="text" name="ind" placeholder=""> <br>
      </div>
      <div class="preferences-form-area" id="preferences-country">
        <label for="country">Country: </label>
        <input class="preference-text" type="text" name="country" placeholder=""> <br>
      </div>
      <div class="preferences-form-area" id="preferences-risk">
        <label for="risk">Risk Rating: </label>
        <input class="preference-text" type="text" name="risk" placeholder="1 (low) to 5 (high)"> <br>
      </div>

    <?php endif ?>
    <button type="submit" class="btn btn-submit" name="submit">Submit Changes</button>


  </form>
</div>

<?php include 'footer.php'; ?>