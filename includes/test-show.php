<?php
$servername = "localhost";
$username = "root";
$password = "";
try {
  $conn = new PDO("mysql:host=$servername;dbname=Products", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully";
} catch(PDOException $e) {
  // echo "Connection failed: " . $e->getMessage();
}


// 31995 Brewer_Assist-Power-Procedure-Deep-Sea-29
// post_type product, product_variation
// wp_posts Post ID 31995
// wp_postmeta Meta ID 848346  Post ID 31995
// wp_postmeta Meta ID 848347  Post ID 31995
/* Execute a prepared statement by passing an array of values */


//  $sql = "SELECT `wp_posts`.`post_title`, `wp_posts`.`ID` FROM `wp_posts` WHERE `post_type` LIKE 'product' OR 'product_variation'";

//24437 
// wp_wc_product_meta_lookup.product_id = wp_posts.ID (where type is prodcut or product_variation)
// 31155
// 31157
// 31158
// 31159
// 24488
// 24604
// 24611
// 24651
// 24692
// 25039
// 25041
// 25043
// 25045

// $sql = "SELECT  * 
// FROM `products`.`wp_postmeta`
// JOIN `products`.`wp_posts`
// ON `wp_postmeta`.`post_id` = `wp_posts`.`ID`
// WHERE `wp_posts`.`post_type` LIKE 'product' 
// LIMIT 10";

// $sql = "SELECT `wp_postmeta`.`post_id`, `wp_postmeta`.`meta_id`, `wp_posts`.`post_title`
//  FROM `products`.`wp_postmeta` 
// JOIN `products`.`wp_posts` 
// ON `wp_postmeta`.`post_id` = `wp_posts`.`ID` 
// WHERE `wp_posts`.`post_type` 
// LIKE 'product' 
// LIMIT 200";

function get_terms($poduct_id, $conn){
    // http://localhost/smartmed1001/public_html/wp-content/uploads/2022/02/Brewer_Assist-Power-Procedure-Deep-Sea-29.jpg
    $sql = "SELECT `meta_id` FROM `wp_postmeta` WHERE `post_id` = $poduct_id";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll();
    return($result[0][0]);
}


  $sql = "SELECT `wp_posts`.`post_title`, `wp_posts`.`ID`, `wp_wc_product_meta_lookup`.`product_id`,`wp_wc_product_meta_lookup`.`max_price`  
  FROM `wp_wc_product_meta_lookup` 
  JOIN `wp_posts` 
  ON `wp_posts`.`ID` = `wp_wc_product_meta_lookup`.`product_id`";
  $sth = $conn->prepare($sql);
  $sth->execute();
  $result = $sth->fetchAll();

print('<br/>');
 print('<p>( '.count($result).' Returned Rows )</p>');


 print('<table class="table">');
 print('<tr><th>#</th><th>Product ID</th><th>POST ID</th><th>META ID</th><th>Price</th><th>Title</th></tr>');
for ($i = 0; $i < 50; $i++) {
print('<tr><td>'.$i.'</td><td>'.$result[$i]['product_id'].'</td><td>'.$result[$i]['ID'].'</td><td>'. get_terms($result[$i]['ID'], $conn).'</td><td>'.$result[$i]['max_price'].'</td><td>'.$result[$i]['post_title'].'</td></tr>');

//print('<tr><td>'.$i.'</td><td>'.$result[$i]['post_title'].'</td><td>'.$result[$i]['post_id'].'</td><td>'.$result[$i]['meta_id'].'</td><td>Product ID</td></tr>');
 }
 print('</table>');


// print('<pre>');
// print_r($result);
// print('</pre>');