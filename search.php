<?php // error_reporting(E_ERROR); ?>
<?php include('./classes/class.conn.php');?>
<?php include('./classes/class.products.php');?>
<?php include('./includes/header.php');?>
<?php include('./includes/nav.php');?>
<?php
 $products_functions = new products;
?>
<div id="wrapper">

<div class="container">
  <div class="row">
  <div class="col-3">
      <?php
        $products_functions->show_all_parent_categories();
      ?>
    </div>
    <div class="col-9">
    <?php
     if(isset($_GET['termid'])){
       print('<h1>'.$products_functions->get_category_name($_GET['termid']).'</h1>');
     // print('<p>Return items ('.$products_functions->search_by_term_id($_GET['termid']).')</p>');
      print('<p>Return items '.$products_functions->search_by_term_and_page_number($_GET['termid'], $_GET['page']).'</p>');
      // create products object 
     }else{
       print('<p>No $_GET[termid] Creatd</p>');
     }
   ?>
      <p>-------------------------------------------</p>
    <?php
     // $products_functions->return_products_by_term();
      $products_functions->return_products_by_term_with_page_number();
    ?>

   <?php
  // print('<p>Count '.count($products_functions->products_object_with_parameters).'</p>');
   ?>
    </div>
  </div>
</div>



   </div> <!-- wrapper -->
   <?php
   $products_functions->pagination_shift_links();
   ?>
<?php include('./includes/footer.php');?>