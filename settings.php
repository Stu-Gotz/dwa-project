<?php include 'header.php';

if (isset($_POST['submit'])) {
  //check if there is a password
  $id = $_SESSION['id'];

  if($_POST['password']){
    if ($_POST['password'] === $_POST['password-conf']){
      $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $sql = "UPDATE `users` SET  password = '$hashed' WHERE id='$id';";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute();

      // $sql2 = "SELECT password FROM users WHERE id=?";
      // $stmt2 = $mysqli->prepare($sql2);
      // $stmt2->bind_param("i", $id);
      // $stmt2->execute();
      // $res = $stmt2->get_result();
      // $record = $res->fetch_assoc();
      
    }
    else {
      echo 'passwords to not match';
    }
  }
  if($_POST['location']){
    $loc = $_POST['location'];
    $sql = "UPDATE `users` SET location = '$loc' WHERE id=$id";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $_SESSION["loc"] = $loc;
  }
  if($_POST['phone']){
    $phone = $_POST['phone'];
    $sql = "UPDATE `users` SET location = '$phone' WHERE id=$id";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $_SESSION["phone"] = $phone;
  }

  header("Location: ./profile.php");
}


?>
<div class="settings-page">
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="settings-form" enctype="multipart/form-data">
    <div class="image-upload">
      <label for="upload">Choose a file to upload: </label>
      <input type="file" name="upload" id="upload">
    </div>
    <div class="location" id="settings-location">
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
    <input type="submit" value="Submit" name="submit">
  </form>
</div>

<?php include 'footer.php'; ?>