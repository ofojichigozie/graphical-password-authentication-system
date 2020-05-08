<?php session_start(); ?>
<?php require_once "includes/database.php"; ?>
<?php require_once "includes/functions.php"; ?>

<?php
    //Track user session ID
    if(!isset($_SESSION["GPAS_user_id"])){
        redirect_to("index.php");
    }

    if(!isset($_SESSION["img_password_set"]) || $_SESSION["img_password_set"] != true){
        redirect_to("setGraphicalPassword.php");
    }

    //Default values of statusMsg and btnClassForSuccess
    $statusMsg = "";
    $btnClassForSuccess = "";

    if(isset($_POST["loginButton"])){
        //Get the selected points on image
        $point00 = trim($_POST["point00"]);
        $point01 = trim($_POST["point01"]);
        $point02 = trim($_POST["point02"]);

        if(!empty($point00) && !empty($point01) && !empty($point02)){
            //SQL statement to update user record
            $updateSQL = "SELECT * FROM users WHERE id = " . $_SESSION["GPAS_user_id"];
            $updateRES = $db->query($updateSQL);
            if($db->num_rows($updateRES) == 1){
                //Fetch array of user's details
                $row = $db->fetch_array($updateRES);
                //Perform image-based authentication
                $gpasResult = gpasCore($point00, $point01, $point02, $row["img_point00"], $row["img_point01"], $row["img_point02"]);
                if($gpasResult == true){
                    $_SESSION["statusMsg"] = $statusMsg = "Welcome <br> <span style='color: #00AA00;'>" . $row["surname"] . ", " . $row["other_names"] . "</span> <br> <span style='font-size: 0.5em; color: #EE0000; font-weight: bold;'>Access Granted!</span>";
                    redirect_to("feedback.php");
                }else{
                    $statusMsg = "Access denied due to wrong image password";
                }
            }else{
                $statusMsg = "Access denied. Could not find the user's ID.";
            }
        }else{
            $statusMsg = "You need to select points from the image";
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
                    <form class="form-inline float-right">
                      <a href="signout.php" class="btn float-right" style="background-color: hsl(192, 100%, 9%); color: #FFFFFF;">Sign out</a>
                    </form>
                </div>
            </nav>
            <div class="landingPage text-center">
                <h2>Authentication System</h2>
                <p>Improving access authentication using image segmentation</p>
            </div>
        </header> 

        <main>
            <div class="form-div">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="form">
                    <div class="form-header">
                        <h4 class="text-center">Sign in <br> <span style="font-size: 0.6em; color: #999911;">Image Password Authentication</span></h4>
                    </div>

                  <?php
                    if(isset($_SESSION["GPAS_user_img"])){

                        $imgFile = $_SESSION["GPAS_user_img"];

                        if(file_exists($imgFile)){

                  ?>
                          <div class="form-group">
                            <div class="text-center <?php if(!empty($statusMsg)) echo "bg-danger"; ?>">
                                <h6 style="padding: 4px; color: #FFFFFF;">
                                    <?php
                                        if(!empty($statusMsg)){
                                            echo $statusMsg;
                                        }
                                    ?>
                                </h6>
                            </div>
                            <label for="surnameInput">Select three points on the image for image password <br> <span style="font-size: 0.7em; font-weight: bold;" id="pointCountDisplay">(No point selected)</span></label>
                            <div style="font-size: 0.7em; font-weight: bold; font-style: italic;">
                                Point 1 <canvas style="width: 12px; height: 12px; border: 0.5px solid hsl(192, 100%, 9%);" id="point00Canvas"></canvas> &nbsp;
                                Point 2 <canvas style="width: 12px; height: 12px; border: 0.5px solid hsl(192, 100%, 9%);" id="point01Canvas"></canvas> &nbsp;
                                Point 3 <canvas style="width: 12px; height: 12px; border: 0.5px solid hsl(192, 100%, 9%);" id="point02Canvas"></canvas>
                                <button class="btn btn-sm <?php echo $btnClassForSuccess; ?>" id="resetButton" style="background-color: hsl(192, 100%, 9%); border: 1px solid #FFFFFF; color: #FFFFFF;">Reset</button>
                            </div>
                            <input type="hidden" id="point00" name="point00" value="">
                            <input type="hidden" id="point01" name="point01" value="">
                            <input type="hidden" id="point02" name="point02" value="">
                          </div>

                          <div class="text-center">
                            <img src="<?php echo $imgFile; ?>" class="img-fluid" id="passwordImg" hidden>
                          </div>

                          <div class="text-center">
                            <canvas id="imgCanvas" class="img-fluid"></canvas>
                          </div>
                          
                          <input type="submit" class="btn btn-secondary <?php echo $btnClassForSuccess; ?>" style="width: 100%; height: 50px; background-color: hsl(192, 100%, 9%);" value="Login" name="loginButton">

                  <?php
                        }else{
                  ?>          
                            <div class="text-center" style="font-weight: bold;">The specified image does not exist</div> 
                  <?php
                        }
                    }else{
                  ?>      
                        <div class="text-center" style="font-weight: bold;">You need an image for graphical password authentication</div> 
                  <?php
                    }
                  ?>
                </form>
            </div>
        </main>
            
        <!-- <footer>
            <div class="container">
                <p>&copy;copy, 2019</p>
            </div>
        </footer> -->
        
        
        <!-- javascript -->
        <script src="Bootstrap/js/jquery-1.11.3.min.js"></script>
        <script src="Bootstrap/js/popper.min.js"></script>
        <script src="Bootstrap/js/bootstrap.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                var image = document.getElementById("passwordImg");
                var canvas = document.getElementById("imgCanvas");

                //Set the size of the canvas to the size of the image
                canvas.width = image.width;
                canvas.height = image.height;

                var ctx = canvas.getContext("2d");
                ctx.drawImage(image, 0, 0);

                var clickCounter = 0;

                $("#imgCanvas").click(function(e){
                    //Check the user has selected upto three points
                    if(clickCounter > 3){
                        return;
                    }

                    var xCoord = e.offsetX;
                    var yCoord = e.offsetY;
                    var imgPixelData = ctx.getImageData(xCoord, yCoord, 1, 1).data;
                    
                    if(clickCounter == 0){
                         var c1 = document.getElementById("point00Canvas").getContext("2d");
                         canvasOperation(c1, imgPixelData);
                         
                         setPixelDataToHiddenInputField("#point00", imgPixelData);

                         $("#pointCountDisplay").html("(1 point selected)");
                    }else if(clickCounter == 1){
                         var c2 = document.getElementById("point01Canvas").getContext("2d");
                         canvasOperation(c2, imgPixelData);

                         setPixelDataToHiddenInputField("#point01", imgPixelData);

                         $("#pointCountDisplay").html("(2 points selected)");
                    }else if(clickCounter == 2){
                         var c3 = document.getElementById("point02Canvas").getContext("2d"); 
                         canvasOperation(c3, imgPixelData);

                         setPixelDataToHiddenInputField("#point02", imgPixelData);

                         $("#pointCountDisplay").html("(3 points selected - <span style='color: #EE0000; font-style: italic;'>Limit Reached</span>)");
                    }

                    clickCounter++;
                });

                $("#resetButton").click(function(e){
                    //Prevent the button from submitting
                    e.preventDefault();

                    //Reset the clickCounter variable
                    clickCounter = 0;  

                    //Reset the hiddenInputFields
                    $("#point00").val("");
                    $("#point01").val("");
                    $("#point02").val("");

                    $("#pointCountDisplay").html("(The selected points were cleared)");
                });

                function canvasOperation(ctx, pixelData){
                    //Get the RGBA value from selcted points
                    var rValue = pixelData[0]; //alert(ctx);
                    var gValue = pixelData[1];
                    var bValue = pixelData[2];
                    // var aValue = pixelData[3];
                    var aValue = 1;

                    ctx.fillStyle = "rgba(" + rValue + ", " + gValue + ", " + bValue + ", " + aValue + ")";
                    ctx.fillRect(0, 0, 1000, 1000);
                }

                function setPixelDataToHiddenInputField(hiddenInputField, pixelData){
                    //Variable to store the sum and average of colors in pixels
                    var sum = 0;
                    var avg = 0;

                    for(var x = 0; x < pixelData.length; x++){
                        sum += pixelData[x];
                    }

                    avg = Math.round(sum / pixelData.length);

                    //Set the avg on the hiddenInputField
                    $(hiddenInputField).val(avg.toString());
                }
            });
        </script>

    </body>
</html>
