<nav class="navbar navbar-default navbar-fixed-top">
   <div class="container">
     <a href="/tutorial/admin/index.php" class="navbar-brand">E-commerce Shop</a>
     <ul class="nav navbar-nav">
       <!-- menu item -->
         <li><a href="index.php">My Dashboard</a></li>
       <li><a href="brands.php">Brands</a></li>
       <li><a href="categories.php">Categories</a></li>
       <li><a href="product.php">Product</a></li>
      <?php if(has_permission('admin')) :?>
         <li><a href="users.php">Users</a></li>
     <?php endif; ?>


     <li class="dropdown">
       <a href="#" class="drop-toggle" data-toggle="dropdown">Hello <?=$user_data['first']; ?>!<span class="caret"></span></a>
       <ul class="dropdown-menu" role="menu">
         <li><a href="change_password.php">Change Password</a></li>
          <li><a href="logout.php">Log Out</a></li>
      <ul>
    </li>
      <!-- <li class="dropdown">
         <a href="#" class="dropdown-toggle++" data-toggle="dropdown"><span class="caret "></span></a>
         <ul class="dropdown-menu" role="menu">

         </ul>
       </li>-->

     </ul>
   </div>
 </nav>
