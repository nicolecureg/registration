<?php
include("config.php");

$Username = $Password = $RepeatPassword = "";
$usernameTaken = false;

if(isset($_POST['Submit'])){
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $RepeatPassword = $_POST['RepeatPassword'];

    // Check if passwords match
    if($Password !== $RepeatPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($Password, PASSWORD_BCRYPT);

        $verify_query = mysqli_query($conn, "SELECT Username FROM baby WHERE Username='$Username'");

        if(mysqli_num_rows($verify_query) != 0){
            $usernameTaken = true;
        } else {
            mysqli_query($conn, "INSERT INTO baby(Username, Password) VALUES('$Username', '$hashedPassword')") or die("Error Occured");

            // Redirect to login page
            header("Location: login.php");
            exit;
        }
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
    <title>Registration</title>
</head>
<body>
    <?php if ($usernameTaken): ?>
    <div class="modal" id="messageBox" tabindex="-1" role="dialog" style="display: block; background: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">This Username is already taken, Please try another one!</h5>
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
            <header>REGISTRATION</header>
            <form action="" method="post" onsubmit="return validatePassword();">

                <div class="field input">
                    <label for="Username">Username:</label>
                    <input type="text" name="Username" id="Username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="Password">Password:</label>
                    <div class="password-input">
                        <input type="password" name="Password" id="Password" autocomplete="off" required>
                        <i class="fas fa-eye-slash toggle-password" onclick="togglePasswordVisibility('Password', this)"></i>
                    </div>
                </div>

                <div class="field input">
                    <label for="RepeatPassword">Repeat Password:</label>
                    <div class="password-input">
                        <input type="password" name="RepeatPassword" id="RepeatPassword" autocomplete="off" required>
                        <i class="fas fa-eye-slash toggle-password" onclick="togglePasswordVisibility('RepeatPassword', this)"></i>
                    </div>
                </div>

                <div class="field">
                    <input type="submit" name="Submit" value="Register">
                </div>

                <div class="links">
                    Already a member? <a href="login.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    function togglePasswordVisibility(inputId, eyeIcon) {
        var input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            input.type = "password";
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    }

    function validatePassword() {
        var password = document.getElementById("Password").value;
        var repeatPassword = document.getElementById("RepeatPassword").value;

        if (password !== repeatPassword) {
            alert("Passwords do not match!");
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }
    </script>
</body>
</html>