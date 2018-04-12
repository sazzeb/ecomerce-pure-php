<?php
  require_once '../core/init.php';
  if(!is_logged_in()){
    login_error_redirect();
  }
  include 'include/head.php';
  include 'include/navigation.php';
  $sql = "SELECT * FROM brand ORDER BY brand";
  $result = $db->query($sql);
  $errors = array();
  //Edit barnd
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = $_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql2 = "SELECT * FROM brand WHERE id = '$edit_id'";
    $edit_result = $db->query($sql2);
    $eBrand = mysqli_fetch_assoc($edit_result);
  }

  //Deleting an Item
  if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delte_id = (int)$_GET['delete'];
    $delete_id = sanitize($delte_id);
    $sql = "DELETE FROM brand WHERE id = '$delte_id'";
    $db->query($sql);
    header('Location: brands.php');
  }

  //if form is submitted
  if(isset($_POST['add_submit'])){
    $brand = sanitize($_POST['brand']);
    //check if brand is empty
    if($_POST['brand'] == ''){
        $errors[] .= 'Please no field must be left empty!';
    }
    //check if brand exist in database, before submitting
    $sql = "SELECT * FROM brand WHERE brand = '$brand'";

    if(isset($_GET['brand'])){
      $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";
    }
    $result= $db->query($sql);
    $count = mysqli_num_rows($result);

    if($count > 0){
      $errors[] .=$brand. '  already exist, please choose another brand name...';
    }
    //display error
    if(!empty($errors)){
      echo display_errors($errors);
    }else {
      # code...
      //Add to cart here if it is successful
      $sql = "INSERT INTO  brand (brand) VALUES ('$brand')";
      if(isset($_GET['edit'])){
        $sql = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
      }
      $db->query($sql);
      header('Location: brands.php');

    }
  }
?>
<h2 class="text-center">Brands</h2><hr>
<!-- Brand Form -->
<div class="text-center">
  <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">
    <div class="form-group">
      <?php
        $brand_value ='';
        if(isset($_GET['edit'])){
          $brand_value = $eBrand['brand'];
        }else {
          # code...
          if(isset($_GET['brand'])){
            $brand_value =sanitize($_GET['brand']);

          }
        }
      ?>
      <label for="brand"><?=((isset($_GET['edit']))?'Edit A':'Add A'); ?> Brand:</label>
      <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value ?>">
      <?php if(isset($_GET['edit'])) : ?>
        <a href="brands.php" class="btn btn-default">Cancel</a>
    <?php endif; ?>
      <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit A':'Add A'); ?> Brand" class="btn btn-success">
    </div><hr>
  </form>
</div>
<table class="table table-bordered table-striped table-auto table-condensed">
  <thead>
    <th></th><th>Brands</th><th></th>
  </thead>
  <tbody>
    <?php while($brand = mysqli_fetch_assoc($result)) : ?>
      <tr>
        <td><a href="brands.php?edit=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><spand class="glyphicon glyphicon-pencil"></spand></a></td>
        <td><?=$brand['brand']; ?></td>
        <td><a href="brands.php?delete=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><spand class="glyphicon glyphicon-remove-sign"></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include 'include/footer.php';?>
