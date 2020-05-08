<?php 
    session_start();
    require_once "includes/functions.php"; 
?>

<?php
  if(isset($_SESSION["GPAS_user_id"])){
    unset($_SESSION["GPAS_user_id"]);
    unset($_SESSION["GPAS_user_img"]);
    unset($_SESSION["img_password_set"]);
    unset($_SESSION["statusMsg"]);
    session_destroy();
    redirect_to("index.php");
  }else{
    echo "<h1 style='text-align: center;'>No logged-in <span style='color: #FF0000;'>user</span> found</h1>";
  }
?>