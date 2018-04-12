<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
    if(!is_logged_in()){
      login_error_redirect();
    }
    include 'include/head.php';
    include 'include/navigation.php';
    //Delete product section
    if(isset($_GET['delete'])){
      $id = sanitize($_GET['delete']);
      $db->query("UPDATE products SET deleted = 1 WHERE id = $id");
      header('Location: product.php');
    }
    $dbpath = '';
    if(isset($_GET['add']) || isset($_GET['edit'])){
    $brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
    $parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
    $title = ((isset($_POST['title']) && $_POST['title'] != '')? sanitize($_POST['title']):'');
    $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
    $category= ((isset($_POST['category']) && !empty($_POST['category']))?sanitize($_POST['category']):'');
    $price = ((isset($_POST['price']) && $_POST['price'] != '')? sanitize($_POST['price']):'');
    $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')? sanitize($_POST['list_price']):'');
    $description = ((isset($_POST['description']) && $_POST['description'] != '')? sanitize($_POST['description']):'');
    $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')? sanitize($_POST['sizes']):'');
    $sizes = rtrim($sizes,',');
    $save_image = '';
    if(isset($_GET['edit'])){
      $edit_id = (int)$_GET['edit'];
      $productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
      $product = mysqli_fetch_assoc($productResults);
      if(isset($_GET['delete_image'])){
        $imgi = (int)$_GET['imgi'] -1;
        $images = explode(',',$product['image']);
        $image_url = $_SERVER['DOCUMENT_ROOT'].$images[$imgi];
        unlink($image_url);
        unset($images[$imgi]);
        $imageString = implode(',',$images);
        $db->query("UPDATE products SET image = '{$imageString}' WHERE id = '$edit_id'");
        header('Location: product.php?edit='.$edit_id);
      }
      $category = ((isset($_POST['child']) && !empty($_POST['child'] != ''))?sanitize($_POST['child']):$product['categories']);
      $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);
      $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$product['brand']);
      $parentResult = $db->query("SELECT * FROM categories WHERE id = '$category'");
      $parentResults = mysqli_fetch_assoc($parentResult);
      $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentResults['parent']);
      $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
      $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):$product['list_price']);
      $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$product['description']);
      $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):$product['sizes']);
      $sizes = rtrim($sizes,',');
      $save_image = (($product['image'] != '')?$product['image']:'');
      $dbpath = $save_image;
    }
    if(!empty($sizes)){
      $sizeString = sanitize($sizes);
      $sizeString = rtrim($sizeString,',');
      $sizesArray = explode(',',$sizeString);
      $sArray = array();
      $qArray = array();
      $tArray = array();
      foreach ($sizesArray as $ss) {
        $s = explode(':', $ss);
        $sArray[] = $s[0];
        $qArray[] = $s[1];
        $tArray[] = $s[2];
      }
    }else {
      $sizesArray = array();
    }
      if($_POST){
        $errors = array();
        $required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
        $allowed = array('png','jpg','jpeg','gif');
        $uploadPath = array();
        $tmpLoc =array();
        foreach($required as $field){
          if($_POST[$field] == ''){
            $errors[] = 'All fileds with asterisk are required.';
            break;
          }
        }
        $photoCount = count($_FILES['photo']['name']);
        if ($photoCount > 0){
          for ($i = 0; $i < $photoCount; $i++){
          $name = $_FILES['photo']['name'][$i];
          $nameArray = explode('.',$name);
          $fileName = $nameArray[0];
          $fileExt = $nameArray[1];
          $mine = explode('/',$_FILES['photo']['type'][$i]);
          $mineType = $mine[0];
          $mineExt = $mine[1];
          $tmpLoc[] = $_FILES['photo']['tmp_name'][$i];
          $fileSize = $_FILES['photo']['size'][$i];
          $uploadName = md5(microtime()).'.'.$fileExt;
          $uploadPath[] = BASEURL.'images/products/'.$uploadName;
            if ($i != 0){
              $dbpath .= ',';
            }
          $dbpath .= '/tutorial/images/products/'.$uploadName;
        if ($mineType != 'image') {
            $errors[] = 'The file must be an image';
          }
          if(!in_array($fileExt, $allowed)){
            $errors[] = 'The file extention must be png, jpg, jpeg, gif';
          }
          if($fileSize > 15000000){
            $errors[] = 'The file size must not be above 15MB.';
          }
          if($fileExt != $mineExt && ($mineExt == 'jpeg' && $fileExt != 'jpg')){
            $errors[] = 'The file extention doesnot match image picture';
          }
          }
       }
        if(!empty($errors)){
          echo display_errors($errors);
       }else {
//          //upload into part
          if($photoCount > 0){
            for ($i = 0; $i <$photoCount; $i++){
               move_uploaded_file($tmpLoc[$i],$uploadPath[$i]);
            }

      }
          $insertSql = "INSERT INTO products (`title`,`price`,`list_price`,`brand`,`categories`,`description`,`sizes`,`image`)
          VALUES ('$title','$price','$list_price','$brand','$category','$description','$sizes','$dbpath')";
          if(isset($_GET['edit'])){
            $insertSql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand',
            categories = '$category', sizes = '$sizes', description = '$description', image = '$dbpath'
            WHERE id = '$edit_id'";
          }
          $db->query($insertSql);
          header('Location: product.php');
        }
      }

    ?>
      <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A New'); ?> PRODUCT HERE</h2>
      <form action="product.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data">
        <div class="form-group col-md-3">
          <label for="title">Title*:</label>
          <input type="text" name="title" class="form-control" id="title" value="<?=$title; ?>">
        </div>
        <div class="form-group col-md-3">
          <label for="brand">Brand*:</label>
          <select class="form-control" id="brand" name="brand">
            <option value="<?=(($brand == '')?' selected':''); ?>"></option>
            <?php while($b = mysqli_fetch_assoc($brandQuery)) : ?>
              <option value="<?=$b['id'];?>"<?=(($brand == $b['id'])?' selected':''); ?> > <?=$b['brand']; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group col-md-3">
          <label for="parent">Parent Category*:</label>
          <select class="form-control" id="parent" name="parent">
            <option value="<?=(($parent == '')?' selected':''); ?>"></option>
            <?php while($p = mysqli_fetch_assoc($parentQuery)) : ?>
              <option value="<?=$p['id'];?>"<?=(($parent == $p['id'])?' selected':''); ?>><?=$p['category']; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group col-md-3">
          <label for="child">Child Category*:</label>
          <select class="form-control" id="child" name="child"></select>
        </div>
        <div class="form-group col-md-3">
          <lable for="price">Price*:</Lable>
          <input type="text" id="price" name="price" class="form-control" value="<?=$price;?>">
        </div>
        <div class="form-group col-md-3">
          <lable for="list_price">List Price:</Lable>
          <input type="text" id="list_price" name="list_price" class="form-control" value="<?=$list_price;?>">
        </div>
        <div class="form-group col-md-3">
          <label>Quantity and Sizes*:</label>
          <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false" >Quantity and Sizes</button>
        </div>
        <div class="form-group col-md-3">
          <label for="sizes">Sizes & Qty Preview*:</label>
          <input type="sizes" class="form-control" name="sizes" id="sizes" value="<?=$sizes;?>" readonly>
        </div>
        <div class="form-group col-md-6" >
          <?php if($save_image != '') : ?>
            <?php
              $imgi = 1;
              $images = explode(',',$save_image);
            ?>
              <?php foreach ($images as $image):?>
            <div class="saved-image col-md-4">
              <img src="<?=$image;?>" alt="save image"/><br>
              <a href="product.php?delete_image=1&edit=<?=$edit_id;?>&imgi=<?=$imgi;?>" class="text-danger">Delete Image</a>
            </div>
              <?php
                $imgi++;
              endforeach;?>
          <?php else: ?>
          <label for="photo">Product Poto:</label>
          <input type="file" name="photo[]" id="photo" class="form-control" multiple>
        <?php endif; ?>
        </div>
        <div class="form-group col-md-6">
          <label for="description">Descrition*:</label>
          <textarea id="description" name="description" class="form-control" rows="6" ><?=$description;?></textarea>
        </div>
        <div class="form-group pull-right">
          <a href="product.php" class="btn btn-default">Cancel</a>
          <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Product" class=" btn btn-success ">
        </div>
      </form>

<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Size & Quantity</h4>
      </div>
      <div class="modal-body">
      <!-- -->
        <div class="container-fluid">
          <?php for($i=1; $i <= 12; $i++) :?>
            <div class="form-group col-md-2">
              <label for="size<?=$i;?>">Size:</label>
              <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:''); ?>" class="form-control">
            </div>
            <div class="form-group col-md-2">
              <label for="qty<?=$i;?>">Quantity:</label>
              <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:''); ?>" min="0" class="form-control">
            </div>
            <div class="form-group col-md-2">
              <label for="threshold<?=$i;?>">Threshold:</label>
              <input type="number" name="threshold<?=$i;?>" id="threshold<?=$i;?>" value="<?=((!empty($tArray[$i-1]))?$tArray[$i-1]:''); ?>" min="0" class="form-control">
            </div>
          <?php endfor; ?>
        <div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php
  }else{
  $sql = "SELECT * FROM products WHERE deleted = 0";
  $presults = $db->query($sql);
  if (isset($_GET['features']) && !empty($_GET['features'])) {
    $id = (int)$_GET['id'];
    $featured = (int)$_GET['features'];
    $featuresql = "UPDATE products SET features = '$featured' WHERE id = '$id'";
    $db->query($featuresql);
    header('Location: product.php');
  }
?>
  <a href="product.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a>
  <div class="clearfix"></div>
  <hr>
  <table class="table table-bordered table-condense table-striped">
    <thead>
      <th></th>
      <th>Product</th><th>Price</th><th>Category</th><th>Feature</th><th>Sold</th>
    </thead>
    <tbody>
      <?php while($product = mysqli_fetch_assoc($presults)) :
        $childID = $product['categories'];
        $catsql = "SELECT * FROM categories WHERE id = '$childID'";
        $result = $db->query($catsql);
        $child = mysqli_fetch_assoc($result);
        $parentID = $child['parent'];
        $psql = "SELECT * FROM categories WHERE id = '$parentID'";
        $presult = $db->query($psql);
        $parent = mysqli_fetch_assoc($presult);
        $category = $parent['category'].' ~ '.$child['category'];
        ?>
      <tr>
        <td>
          <a href="product.php?edit=<?= $product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
          <a href="product.php?delete=<?= $product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
        <td><?= $product['title']; ?></td>
        <td><?=money($product['price']); ?></td>
        <td><?=$category;?></td>
        <td><a href="product.php?features=<?=(($product['features'] == 0)?'1':'0'); ?> & id=<?=$product['id']; ?>" class="btn btn-xs btn-default">
          <span class="glyphicon glyphicon-<?=(($product['features'] == 1)?'minus':'plus');?>"></span>
        </a>&nbsp <?=(($product['features'] == 1)?'Featured Product':'');?></td>
        <td >0</td>
      </tr>
    <?php endwhile; ?>
    <tbody>
  </table>
  <?php  } include 'include/footer.php';?>
<script>
  jQuery('document').ready(function(){
    get_child_options('<?=$category; ?>');
  });
</script>
