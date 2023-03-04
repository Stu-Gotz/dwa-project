<?php
if (isset($_POST['submit'])) {
    echo $_POST['name'];
}
?>

<form action="$_SERVER['PHP_SELF']" method="POST">
    <label for="name">Name: </label>
    <input type="text" name="name">
    <input type="submit" value="Submit" name="submit">
</form>