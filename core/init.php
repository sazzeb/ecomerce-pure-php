<?php
  $db = mysqli_connect('127.0.0.1','root','','tutorial');
  if(mysqli_connect_errno()){
      echo 'Fail to connect to database, please check your connection: '. mysqli_connect_error();
      die();
  }
    session_start();
  //this used to show the part if we are working part on the admin, it worked
  //but will break if we go back to our root directory, the solution is to create altimate partlike this.

  //require_once '../config.php';
  require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/config.php';
  require_once BASEURL.'/helpers/helpers.php';
  require BASEURL. '/vendor/autoload.php';

  $cart_id = '';
  if(isset($_COOKIE[CART_COOKIE])){
    $cart_id = sanitize($_COOKIE[CART_COOKIE]);

  }
  //creating SessionHandler
  //session is a phpfunction that show users log in details on a server
  if(isset($_SESSION['SBUser'])){
    $user_id = $_SESSION['SBUser'];
    $Query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
    $user_data = mysqli_fetch_assoc($Query);
    $fn = explode(' ', $user_data['full_name']);
    $user_data['first'] = $fn[0];
    $user_data['last'] = $fn[1];
  }
  if(isset($_SESSION['success_flash'])){
    echo '<div class="bg-success"><p class="text-success text-center midpoint-river"> '.$_SESSION['success_flash'].'</p></div>';
    unset($_SESSION['success_flash']);
  }
  if(isset($_SESSION['error_flash'])){
    echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
    unset($_SESSION['error_flash']);
  }
?>
