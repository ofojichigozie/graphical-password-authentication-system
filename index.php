<?php session_start(); ?>
<?php require_once "includes/database.php"; ?>
<?php require_once "includes/functions.php"; ?>

<?php
    //Set default user details for sign in
    $signInEmail = "";
    $signInPassword = "";

    //Messages variable for signIn
    $loginStatusMsg = $statusMsg = "";

    if(isset($_POST["signInButton"])){

        //Get the user's sign in details
        $signInEmail = trim($_POST["signInEmail"]);
        $signInPassword = trim($_POST["signInPassword"]);

        if(!empty($signInEmail) && !empty($signInPassword)){
            $hashedPassword = md5(md5($signInPassword)); //Hash the password
            $sql = "SELECT * FROM users WHERE email='$signInEmail' AND password='$hashedPassword' LIMIT 1";
            $res = $db->query($sql);
            //Check if user was found
            if($db->num_rows($res) == 1){
              $row = $db->fetch_array($res);
              //Set session variables for ID and image filename
              $_SESSION["GPAS_user_id"] = $row["id"];
              $_SESSION["GPAS_user_img"] = $row["image_filename"];
              //Check if the user has set image password before
              if($row["img_point00"] != NULL & $row["img_point01"] != NULL & $row["img_point02"] != NULL){
                //Session variable to track if user has already set the image password
                $_SESSION["img_password_set"] = true;
                redirect_to("graphicalPasswordAuthentication.php");
              }else{
                //Session variable to track if user has already set the image password
                $_SESSION["img_password_set"] = false;
                redirect_to("setGraphicalPassword.php");
              }
            }else{
              $loginStatusMsg = "Unauthorized access denied";
            }
        }else{
            $loginStatusMsg = "You did not provide the login details";
        }

    }

    //Set default user details for sign up
    $surname = "";
    $otherNames = "";
    $email = "";
    $phone = "";
    $password = "";
    $rePassword = "";

    //Array to hold errors
    $errorArray = array();

    if(isset($_POST["signUpButton"])){
        //Get user's sign up details
        $surname = trim($_POST["surname"]);
        $otherNames = trim($_POST["otherNames"]);
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phone"]);
        $password = trim($_POST["password"]);
        $rePassword = trim($_POST["rePassword"]);

        if(empty($surname)){
            $errorArray[] = "Surname";
            $statusMsg = "Surname is empty";
        }

        if(empty($otherNames)){
            $errorArray[] = "Other Names";
            $statusMsg = "Other names is empty";
        }

        if(empty($email)){
            $errorArray[] = "Email";
            $statusMsg = "Email is empty";
        }

        if(empty($phone)){
            $errorArray[] = "Phone number";
            $statusMsg = "Phone number is empty";
        }

        if(empty($password)){
            $errorArray[] = "Password";
            $statusMsg = "Password is empty";
        }

        if(empty($rePassword)){
            $errorArray[] = "Confirm Password";
            $statusMsg = "Confirm password is empty";
        }

        //Check if there is any error
        if(empty($errorArray)){
            //Check if the passwords match
            if($password == $rePassword){
                //Hash the password using DOUBLE MD5
                $hashedPassword = md5(md5($password));

                //Upload the profile image
                $uploadResult = handleImageUpload($hashedPassword);

                if($uploadResult["status"] == "success"){
                    //Get the name of the image file
                    $passwordImageName = $uploadResult["message"];

                    //SQL statement
                    $registerSQL = "INSERT INTO users(surname, other_names, email, phone, password, image_filename, img_point00, img_point01, img_point02) VALUES('$surname', '$otherNames', '$email', '$phone', '$hashedPassword', '$passwordImageName', NULL, NULL, NULL)";
                    $registerRES = $db->query($registerSQL);
                    if($db->affected_rows($registerRES) == 1){
                        /*startCode - QUERY DATABASE TO GET USER ID*/
                        $sql = "SELECT * FROM users WHERE email='$email' AND password='$hashedPassword' LIMIT 1";
                        $res = $db->query($sql);
                        if($db->num_rows($res) == 1){
                            //Fetch USER details and set session ID variable
                            $row = $db->fetch_array($res);
                            $_SESSION["GPAS_user_id"] = $row["id"];
                            $_SESSION["GPAS_user_img"] = $row["image_filename"];
                            $_SESSION["img_password_set"] = false;
                            redirect_to("setGraphicalPassword.php");
                        }
                        /*stopCode - QUERY DATABASE TO GET USER ID*/
                    }else{
                      $statusMsg = "Registration failed";
                    }
                }else{
                    $statusMsg = $uploadResult["message"]; echo $message;
                }
            }else{
                $statusMsg = "The passwords do not match";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Graphical Password Authentication</title>
        <link href="Bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="Bootstrap/css/Style.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <nav>
                <div class="container">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form-inline float-right">
                      <div class="form-group">
                        <label style="font-size: 0.7em; font-weight: bold; color: #FF0000;"><?php if(!empty($loginStatusMsg)) { echo $loginStatusMsg; } ?> &nbsp; </label>
                      </div>
                      <div class="form-group">
                        <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Email" name="signInEmail" required>
                      </div>
                      <div class="form-group">
                        <input type="password" class="form-control" aria-describedby="passwordHelp" placeholder="Password" name="signInPassword" required>
                      </div>
                      <input type="submit" class="btn" style="background-color: hsl(192, 100%, 9%); color: #FFFFFF;" value="Sign in" name="signInButton">
                    </form>
                </div>
            </nav>
            <div class="landingPage text-center">
                <h2>Authentication System</h2>
                <p>Improving access authentication using image segmention</p>
            </div>
        </header> 

        <main>
            <div class="form-div">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="form">
                    <div class="form-header">
                        <h4 class="text-center">Sign up</h4>
                    </div>
                    <div class="text-center text-danger">
                        <h5>
                            <?php
                                if(!empty($statusMsg)){
                                    echo $statusMsg;
                                }
                            ?>
                        </h5>
                    </div>
                  <div class="form-group">
                    <label for="surnameInput">Surname</label>
                    <input type="text" class="form-control" aria-describedby="surnameHelp" id="surnameInput" name="surname" required>
                  </div>
                  <div class="form-group">
                    <label for="otherNamesInput">Other Names</label>
                    <input type="text" class="form-control" aria-describedby="otherNamesHelp" id="otherNamesInput" name="otherNames" required>
                  </div>
                  <div class="form-group">
                    <label for="emailInput">Email</label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" id="emailInput" name="email"required>
                  </div>
                  <div class="form-group">
                    <label for="phoneInput">Phone Number</label>
                    <input type="text" class="form-control" aria-describedby="phoneHelp" id="phoneInput" name="phone" required>
                  </div>
                  <div class="form-group">
                    <label for="passwordInput">Password</label>
                    <input type="password" class="form-control" id="passwordInput" name="password" required>
                  </div>
                  <div class="form-group">
                    <label for="rePasswordInput">Re-enter Password</label>
                    <input type="password" class="form-control" id="rePasswordInput" name="rePassword" required>
                  </div>
                  <div class="form-group">
                      <label for="exampleFormControlFile1">Select an image file for the image authentication <br> <span style="font-size: 0.8em;">(Maximum dimension is <span style="color: #990000;">600</span>x<span style="color: #990000;">600</span>)</span></label>
                      <input type="file" class="form-control-file" id="file" name="file">
                    </div>
                  <input type="submit" class="btn btn-secondary" style="width: 100%; height: 50px; background-color: hsl(192, 100%, 9%);" value="Submit" name="signUpButton">
                </form>
            </div>
        </main>
            
        <footer>
            <div class="container">
                <p>&copy;copy, 2019</p>
            </div>
        </footer>
        
        
        <!-- javascript -->
        <script src="Bootstrap/js/jquery-1.11.3.min.js"></script>
        <script src="Bootstrap/js/popper.min.js"></script>
        <script src="Bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>
