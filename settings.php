<?php include 'header.php'; ?>
<div class="settings-page">
  <form action="changeSettings.php" method="POST" class='settings-form'>
    <div class="image-upload">
      <label for="image">Choose a file to upload: </label>
      <input type="file" name="image" id="image">
    </div>
    <div class="location">
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
    <input type="submit" value="Submit">
  </form>
</div>

<?php include 'footer.php'; ?>