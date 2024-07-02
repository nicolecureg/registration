<?php
include("config.php");
$Incorrect=false;
if(isset($_POST['Submit'])){
    $Username = mysqli_real_escape_string($conn, $_POST['Username']);
    $Password = mysqli_real_escape_string($conn, $_POST['Password']);

    // Fetch the user record matching the exact case-sensitive username
    $result = mysqli_query($conn, "SELECT * FROM baby WHERE BINARY Username='$Username'") or die("Error Occurred");
    $row = mysqli_fetch_assoc($result);

    if(is_array($row) && !empty($row) && password_verify($Password, $row['Password'])){
        $_SESSION['Username'] = $row['Username'];
        $_SESSION['Password'] = $row['Password'];
        header("Location: homepage.php");
        exit;
    } else {
        $Incorrect=true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <title>Login</title>
</head>
<body>
<?php if ($Incorrect): ?>
    <div class="modal" id="messageBox" tabindex="-1" role="dialog" style="display: block; background: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Incorrect Username or Password!</h5>
                    <button type="button" class="btn-close" aria-label="Close" onclick="closeMessageBox()"></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function closeMessageBox() {
            document.getElementById('messageBox').style.display = 'none';
        }
    </script>
    <?php endif; ?>

<div class="container">
    <div class="box form-box">
        <header>Login</header>
        <form action="" method="post">
            <div class="field input">
                <label for="Username">Username:</label>
                <input type="text" name="Username" id="Username" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="Password">Password:</label>
                <input type="password" name="Password" id="Password" autocomplete="off" required>
            </div>

            <div class="field">
                <input type="submit" name="Submit" value="Login" required>
            </div>

            <div class="links">
            Application for New Student <a href="index.php"> Register</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>