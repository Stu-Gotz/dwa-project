<?php include 'header.php';

//keep pages private from non-registered users
if (!isset($_SESSION['userid'])) {
  header('Location: ./login.php');
}
$id = $_SESSION['id'];

if (isset($_POST['submit'])) {

  // Logic to handle what is updated, since not everything may be changed at one time.
  // Password Change
  if (isset($_POST['password']) && !($_POST['password'] === "")) { 
    if (isset($_POST['password-conf']) && !($_POST['password-conf'] === "")) { 
      if(filter_var(htmlspecialchars($_POST['password']), FILTER_SANITIZE_SPECIAL_CHARS) && 
      filter_var(htmlspecialchars($_POST['password-conf']), FILTER_SANITIZE_SPECIAL_CHARS)) {
        $password = htmlspecialchars($_POST['password']);
        $passconf = htmlspecialchars($_POST['password-conf']);
        if (htmlspecialchars($password) === htmlspecialchars($passconf)) {
          $hashed = password_hash($password, PASSWORD_DEFAULT);
          $sql = "UPDATE `users` SET  password = '$hashed' WHERE `users`.`id`=?;";
          $mysqli->execute_query($sql, [$id]);
          $_SESSION['password'] = $hashed;
        } else {
          echo 'passwords to not match';
        }
      }
    }
  }
  // Location Change
  if (isset($_POST['location'] ) && !($_POST['phone'] === "")) {
    if(filter_var(htmlspecialchars($_POST['location']), FILTER_SANITIZE_SPECIAL_CHARS)){
      $loc = htmlspecialchars($_POST['location']);
      $sql = "UPDATE `users` SET location = '$loc' WHERE id=?";
      $mysqli->execute_query($sql, [$id]);
      $_SESSION["loc"] = $loc;
    }
    
  }
  // Phone Number Change
  if (isset($_POST['phone']) && !($_POST['phone'] === "")) {
    $phone = preg_replace('/[^0-9+-]/', '', htmlspecialchars($_POST['phone']));
    $mysqli->execute_query("UPDATE `users` SET phone = '$phone' WHERE id=?", [$id]);
    $_SESSION["phone"] = $phone;
  }
  if (isset($_POST['email']) && !($_POST['email'] === "")) {
    if(filter_var(htmlspecialchars($_POST['email']), FILTER_SANITIZE_EMAIL)){
      $email = htmlspecialchars($_POST['email']);
      $mysqli->execute_query("UPDATE `users` SET email = '$email' WHERE id=?", [$id]);
      $_SESSION["email"] = $email;
    }
    
  }
  // File upload 
  /* 
  $uploadOK is use to determine if the upload is valid. 
  Conditional statements check if the parameters required are met, and if not, sets $uploadOk to 0,
  which will display an error message.

  An MD5 hash is used to store file uploads, to ensure there is not a conflict with similarly named files.
  This is not guaranteed, but since MD5 has 2**28 possible values, it is very unlikely.

  */
  if (isset($_FILES['upload']) && is_uploaded_file($_FILES['upload']['tmp_name'])) {
    $uploadOk = 1;
    $path = "./assets/";
    $tmp_name = $_FILES['upload']['tmp_name'];
    $name = $_FILES['upload']['name'];
    $hash = md5($name);

    $filetype = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $file_path = $path . $hash . '.' . $filetype;
    $filename = $hash . '.' . $filetype;

    if ($_FILES['upload']['size'] > 10485760) {
      array_push($_SESSION['errors'], "Filesize too large");
      echo "Filesize too large, uploads must be under 10mb";
      $uploadOk = 0;
    } else 

    if ($filetype != "jpg" && $filetype != "png" && $filetype != "jpeg" && $filetype != "bmp" && $filetype != "svg") {
      array_push($_SESSION['errors'], "Unsupported filetype");
      echo "We only accept files in format: JPG, PNG, JPEG, BMP or SVG.";
      $uploadOk = 0;
    } else 

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

    /*
    This conditional statement checks if a client is logged in, since only
    clients can modify their preferences, this code should not execute for other users when
    they modify their settings.
    */
    if ((isset($_SESSION['type']) && $_SESSION['type'] === 'client')) {
      /*
      Initialize variables to empty strings or NULL so that the SQL query will still execute.
      They will change if values are provided and pass the check.
      */
      $type = "";
      $country = "";
      $ind= "";
      $risk = NULL;
  
      if (!is_null(htmlspecialchars($_POST['type'])) && filter_var(htmlspecialchars($_POST['type']), FILTER_SANITIZE_SPECIAL_CHARS)) {
        $type = htmlspecialchars(htmlspecialchars($_POST['type']));
      }
      if (!is_null(htmlspecialchars($_POST['ind'])) && filter_var($_POST['ind'], FILTER_SANITIZE_SPECIAL_CHARS)) {
        $ind = htmlspecialchars(htmlspecialchars($_POST['ind']));
      }
      if (!is_null($_POST['country']) && filter_var(htmlspecialchars($_POST['country']), FILTER_SANITIZE_SPECIAL_CHARS)) {
        $country = htmlspecialchars($_POST['country']);
      }
      if (!is_null($_POST['risk']) && filter_var($_POST['risk'], FILTER_SANITIZE_NUMBER_INT)) {
        $risk = htmlspecialchars($_POST['risk']);
      }


      $sql = "INSERT INTO client_prefs (id, client_id, 'type', ind, country, risk) VALUES ('$id', '$type', '$ind', '$country', '$risk');";

      $mysqli_query($sql);
    }
  }
}
elseif(isset($_POST['reset'])) {
  $sql = "DELETE FROM client_prefs WHERE client_id = " . $id;
  $res = $mysqli->query($sql);
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
    <div class="phone">
      <label for="email">Email: </label>
      <input type="text" name="email" id="phone" placeholder="account@example.com">
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
      <button type="submit" class="btn btn-del" name="reset">Reset Preferences</button>
    <?php endif ?>
    <button type="submit" class="btn btn-submit" name="submit">Submit Changes</button>
    

  </form>
</div>

<?php include 'footer.php'; ?>