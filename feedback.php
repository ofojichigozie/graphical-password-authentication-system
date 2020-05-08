<?php session_start(); ?>
<?php require_once "includes/database.php"; ?>
<?php require_once "includes/functions.php"; ?>

<?php
    if(!isset($_SESSION["statusMsg"])){
        redirect_to("index.php");
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

        <style type="text/css">
            @keyframes back-anim{
              0% { background-color: hsl(192, 100%, 9%); }
              25% { background-color: rgba(0, 0, 0, 0.9); }
              50% { background-color: hsl(192, 100%, 9%); }
              75% { background-color: rgba(0, 0, 0, 0.9); }
              100% { background-color: hsl(192, 100%, 9%); }
            }

            body{
                background-color: rgba(0, 0, 0, 0.3);

                background-color: hsl(192, 100%, 9%);
                animation-name: back-anim;
                animation-duration: 3s;
                animation-delay: 2s;
                animation-iteration-count: infinite;
            }

            #container{
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .feed-back-div{
                position: relative;
                top: 10em;
                border-radius: 1em;
                box-shadow: 0 0 12px -2px rgba(0, 0, 0, 0.6);
                width: 85%;
                height: 300px;
                background-color: #FFFFFF;
            }

            .feed-back-div-main{
                height: 60%;
                display: flex;
                justify-content: center;
                align-items: center;
                font-weight: bold !important;
            }

            .feed-back-div-main h3{
                font-size: 1em;
            }

            .feed-back-div-footer{
                height: 40%;
                padding: 20px;
            }

            @media only screen and (min-width: 700px){
                .feed-back-div{
                    width: 50% !important;
                }

                .feed-back-div-main h3{
                    font-size: 1.8em;
                }
            }
        </style>
    </head>
    <body>
        <nav>
            <div class="container">
                <a href="signout.php" class="btn btn-secondary float-right">Sign out</a>
            </div>
        </nav>

        <div class="container" id="container">
            <div class="feed-back-div">
                <div class="feed-back-div-main text-center">
                    <h3><?php if(isset($_SESSION["statusMsg"])){ echo $_SESSION["statusMsg"]; }?></h3>
                </div>
                <div class="feed-back-div-footer">
                    <hr style="border-width: 2px;">
                    <a class="btn btn-secondary float-right" href="index.php">OK</a>
                </div>
            </div>
        </div>
        <!-- javascript -->
        <script src="Bootstrap/js/jquery-1.11.3.min.js"></script>
        <script src="Bootstrap/js/popper.min.js"></script>
        <script src="Bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>
