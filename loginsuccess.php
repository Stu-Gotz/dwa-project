<?php 
include 'header.php';



if(isset($_SESSION['name'])){
    echo '<h2>You have successfully logged-in! You will be redirected to your homepage in <span id="counter">5</span> seconds...</h2>
    <br>
    <a style="font-size=20px;" href="./profile.php">Click here to skip.</a>';
    header('refresh: 5, url=./profile.php');
} else {
    echo '<h2>You are not logged in. Please return to the <a href="./login.php">login page</a> and try again.</h2>';
}
?>

<script type="text/javascript">
    function count() {
        let i = document.getElementById('counter');
        let val = parseInt(i.innerHTML);

        // if (val<=0){
        //     location.href = 'profile.php';
        // }
        if (val != 0){
            val -= 1;
            i.innerHTML = val;
        }
    }
    setInterval(function() { count(); }, 1000);

</script>

<?php include 'footer.php'; ?>