<?php include 'header.php';

if (isset($_POST['submit'])) {
  if (!empty($_FILES['upload']['name'])) {
    print_r($_FILES);
  } else {
    $message = '<p style="color: red;">Please choose a valid file</p>';
  }
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