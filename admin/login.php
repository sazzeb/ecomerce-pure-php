<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
    include 'include/head.php';
    $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $email = trim($email);
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $password = trim($password);
    $errors = array();
?>
<style>
body{
  background-image:url("/tutorial/images/headerlogo/background.png");
  background-size: 100vw 100vh;
  background-attachment: fixed;
}
</style>
<div id="login-form">
  <div>
    <?php
      if($_POST){
        //form validation
        if(empty($_POST['email']) || empty($_POST['password'])){
          $errors[] = 'Enter email and password';
        }
        //validate email
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
          $errors[] = 'you must enter a valid email address';
        }
        //check for password strength
        if(strlen($password) <= 6 ){
        $errors[] ='your password must be more than 6 characters';
        }

        //check if the email exist in the database
          $query = $db->query("SELECT * FROM users WHERE email = '$email'");
          $users = mysqli_fetch_assoc($query);
          $usercount = mysqli_num_rows($query);
          if($usercount < 1){
            $errors[] = 'The email or password is invalid, retry!';
          }
          if(!password_verify($password,$users['password'])){
            $errors[] = 'Please the password you enter is incorrect, retry!';
          }
        //check error
        if(!empty($errors)){
          echo display_errors($errors);
        }else{
          $user_id = $users['id'];
          login($user_id);
        }
      }?>
  </div>
  <h2 class="text-center">Login</h2><hr>
  <form action="login.php" method="post">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>
    <div class="form-group">
      <input type="submit" value="Login" class="btn btn-primary">
    </div>
  </form>
  <p class="text-right"><a href="/tutorial/index.php" alt="home">Visit Site</a></p>
</div>
<?php include 'include/footer.php' ?>
