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
      print('<p>Return items ('.$products_functions->search_by_term_id($_GET['termid']).')</p>');
     }else{
       print('<p>No $_GET[termid] Creatd</p>');
     }
  //   $products_functions->create_products_object_with_parameters();
  //  if((isset($_GET['page']))){
  //      $page = $_GET['page'];
  //      $products_functions->loop_through_products_object_with_parameters($page);
  //   }else{
  //      $products_functions->loop_through_products_object_with_parameters(1);
  //   }
   ?>
      <p>-------------------------------------------</p>
    <?php
      $products_functions->return_products_by_term();
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