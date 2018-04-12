<?php
/**
 * Created by PhpStorm.
 * User: sazzeb
 * Date: 1/4/17
 * Time: 10:36 AM
 */
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';
include 'includes/leftbar.php';


$sql = "SELECT * FROM products ";
$cat_id = (($_POST['cat'] != '')?sanitize($_POST['cat']):'');
if($cat_id == ''){
    $sql .= 'WHERE deleted = 0';
}else{
    $sql .= " WHERE categories = '{$cat_id}' AND DELETED = 0";
}
$price_sort = (($_POST['price_sort'] != '')?sanitize($_POST['price_sort']):'');
$min_price = (($_POST['min_price'] != '')?sanitize($_POST['min_price']):'');
$max_price = (($_POST['max_price'] != '')?sanitize($_POST['max_price']):'');
$brand = (($_POST['brand'] != '')?sanitize($_POST['brand']):'');
if ($min_price != ''){
    $sql .= " AND price >= '{$min_price}'";
}
if ($max_price != ''){
    $sql .= " AND price <= '{$max_price}'";
}
if($brand != ''){
    $sql .= " AND brand = '{$brand}'";
}
if($price_sort == 'low'){
    $sql .= " ORDER BY price";
}
if($price_sort == 'high'){
    $sql .= " ORDER BY price DESC";
}
$productQ = $db->query($sql);
$category = get_category($cat_id);
?>

<!-- center bar -->
<div class="col-md-8">
    <div class="row">
        <?php if ($cat_id != ''):?>
        <h2 class="text-center"><?=$category['parent']. ' ' .$category['child'];?></h2>
        <?php else :?>
        <h2 class="text-center">E-Commerce Shopping Center</h2>
        <?php endif;?>
        <?php while($product = mysqli_fetch_assoc($productQ)) : ?>
            <div class="col-md-3">
                <h4><?= $product['title']; ?></h4>
                <?php $photos = explode(',',$product['image']);?>
                <img src="<?= $photos[0]; ?>" alt="<?= $product['title']; ?>" class="img-thumb" />
                <p class="list-price text-danger">List Price <s>$<?= $product['list_price']; ?></s></p>
                <p class="price">Our Price: $<?= $product['price']; ?></p>
                <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id']; ?>)">Details</button>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php
/** we are not including this page reason been that we are
going to use ajax to request for the url

include 'includes/detailsmodal.php';

 */
include 'includes/rightbar.php';
include 'includes/footer.php';
?>
