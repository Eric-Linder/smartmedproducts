<?php
class products{

    public $conn;
    public $products_object;
    public $products_object_with_parameters;
    public $return_products_by_term;
    public $termid;
    public $page;
    public function __construct(){
        $servername = "localhost";
        $username   = "root";
        $password   = "";
        try {
            $conn = new PDO("mysql:host=$servername;dbname=Products", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           // echo "Connected successfully";
            $this->conn = $conn;
        }
        catch (PDOException $e) {
           // echo "Connection failed: " . $e->getMessage();
        }
    }


    public function create_products_object_with_parameters($category="1", $tag="1"){
        $sql = "SELECT `wp_posts`.`post_title`, `wp_posts`.`ID`, `wp_wc_product_meta_lookup`.`product_id`,`wp_wc_product_meta_lookup`.`max_price`, `wp_wc_product_attributes_lookup`.`product_or_parent_id` 
        FROM `wp_wc_product_meta_lookup` 
        JOIN `wp_posts` 
        ON `wp_posts`.`ID` = `wp_wc_product_meta_lookup`.`product_id`
        JOIN `wp_wc_product_attributes_lookup`
        ON `wp_posts`.`ID` = `wp_wc_product_attributes_lookup`.`product_or_parent_id`
        WHERE 1 = 1 
        GROUP BY(`wp_posts`.`ID`);
        ";
        $sth = $this->conn->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();
        $this->products_object_with_parameters = $result;
        //print_r($this->conn);
    }


    public function create_products_object(){
        $sql = "SELECT `wp_posts`.`post_title`, `wp_posts`.`ID`, `wp_wc_product_meta_lookup`.`product_id`,`wp_wc_product_meta_lookup`.`max_price`  
        FROM `wp_wc_product_meta_lookup` 
        JOIN `wp_posts` 
        ON `wp_posts`.`ID` = `wp_wc_product_meta_lookup`.`product_id`";
        $sth = $this->conn->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();
        $this->products_object = $result;
        print_r($this->conn);
    }

    public function loop_through_products_object($start_at=1){
        $products_object = $this->products_object;
        $count=0;
        $count = count($products_object);
       $start_at = $start_at*30;
       $start_at = $start_at-30;
        print('<table class="table">');
       // print('<tr><th colspan="6">'.count($products_object).'</th></tr>');
        print('<tr><th>Row</th><th>Product</th><th>POST</th><th>META</th><th>Price</th><th>Title</th></tr>');
        for ($i = $start_at;  $i < ($start_at+30); $i++) {
            print('<tr><td>'.$i.'</td><td>'.$products_object[$i]['product_id'].'</td><td>'.$products_object[$i]['ID'].'</td><td></td><td>'.$products_object[$i]['max_price'].'</td><td>'.$products_object[$i]['post_title'].'</td></tr>');
            
             }
             print('</table>');
    }

    public function loop_through_products_object_with_parameters($start_at=1){
        $products_object_with_parameters = $this->products_object_with_parameters;
        $count=0;
        $count = count($products_object_with_parameters);
       $start_at = $start_at*30;
       $start_at = $start_at-30;
        print('<table class="table">');
       // print('<tr><th colspan="6">'.count($products_object_with_parameters).'</th></tr>');
        print('<tr><th>Row</th><th>Product</th><th>POST</th><th>TERMS</th><th>Price</th><th>Title</th></tr>');
        for ($i = $start_at;  $i < ($start_at+30); $i++) {
            $terms = $this->terms_associated_with_product_id($products_object_with_parameters[$i]['product_id']);
            print('<tr>
                <td>'.$i.'</td>
                <td>'.$products_object_with_parameters[$i]['product_id'].'</td>
                <td>'.$products_object_with_parameters[$i]['ID'].'</td>
                <td>'.$terms.'</td>
                <td>'.$products_object_with_parameters[$i]['max_price'].'</td>
                <td>'.$products_object_with_parameters[$i]['post_title'].'</td>
            </tr>');
             }
             print('</table>');
    }

    public function terms_associated_with_product_id($product_id=1){
         $sql = "SELECT * FROM `wp_wc_product_attributes_lookup` WHERE `product_or_parent_id` = ".$product_id." ORDER BY `product_or_parent_id` DESC";
         $sth = $this->conn->prepare($sql);
         $sth->execute();
         $result = $sth->fetchAll();
        return(count($result));
    }

    public function pagination_array($products_object, $page = 1){
        // divide numbers of product row by 30 items per-page
        // to find out how many pages are needed. Add 1 to the number 
        // to show the remainder on the last page
        // convert to whole number plus 1
        $number_of_pages = (int) count($products_object) / 30;
        $number_of_pages = (int)$number_of_pages + 1;
        // build an array that has an element for every page needed.
        // give each element a value of 30. 30 items per page.
        // start with indice "1" so you can mulitply by 30 in loop
        $array_of_page_link_start_values = array_fill(1, $number_of_pages, 30);
        //  look like this -->  Array([1] => 30 [2] => 30 [3] => 30...);
        foreach ($array_of_page_link_start_values as $key => $value) { 
            $start_at_row = (int)$key * 30;
            $start_at_row =  $start_at_row-30;
            print('<p>Page '.$key.' Start at row '.$start_at_row .' and stop at row '.$value.'</p>');
        }
        // number of page links to show at any given time
       // $number_of_links_to_show = 6;
       // print('<p> Number of page links '.$number_of_pages.'</p>');
        // print('<pre>');
        // print_r($array_of_page_link_start_values);
        // print('</pre>');
        // Northwell 1085 Park Ave 212 876 1886
    }

    public function show_all_parent_categories(){
        //$sql = "SELECT * FROM `wp_terms`";
        $sql = "SELECT * FROM `wp_term_taxonomy` 
        JOIN `wp_terms` 
        ON `wp_terms`.`term_id` = `wp_term_taxonomy`.`term_id` 
        WHERE `wp_term_taxonomy`.`taxonomy` 
        LIKE 'product_cat' 
        AND `wp_term_taxonomy`.`parent` = 0";
        $sth = $this->conn->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();
        $num = count($result);

        print('<table class="table" id="category_terms_table">');
        for($i=0; $i < $num; $i++){
            // print('<tr><td><a href="search.php?termid='.$result[$i]['term_id'].'&page=1">'.$result[$i]['name'].'</a></td><td><a href="#"><i class="fa fa-caret-square-o-right" aria-hidden="true"></i></a></td></tr>');
            print('<tr><td><a href="search.php?termid='.$result[$i]['term_id'].'&page=1">'.$result[$i]['name'].'</a></td><td>'.$this->number_of_items_in_category($result[$i]['term_id']).'</td></tr>');
        }
        print('</table>');
    }

    
    public function number_of_items_in_category($termid){
        $sql = "SELECT `wp_posts`.`post_title`
        FROM `wp_term_relationships`
        JOIN `wp_posts`
        ON `wp_term_relationships`.`object_id` = `wp_posts`.`ID`
        WHERE `wp_term_relationships`.`term_taxonomy_id` = :TERMID";
        $sth = $this->conn->prepare($sql);
        $sth->bindValue(":TERMID", $termid, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchall();
        $items_in_category = count($result);
        return $items_in_category;
    }



   public function pagination_shift_links($start_at = 1, $end_at=6){

    if (isset($_GET['page'])){
        $start_at = (int)$_GET['page'];
        $end_at = $start_at + 6;
    }else{
        $start_at = 1;
        $end_at=6;
    }

    if (isset($_GET['previous'])){
        $start_at = $start_at-1;
        $end_at = $end_at-1;
    }
    if (isset($_GET['forward'])){
        $start_at = $start_at+1;
        $end_at = $end_at+1;
    }

        print'
        <footer>
          <div>
            <nav aria-label="Page navigation">
            <ul class="pagination d-flex justify-content-center">';
                print '<li class="page-item"><a class="page-link" href="?page='.$start_at.'&previous=back">Previous</a></li>';

                for($i=$start_at; $i < $end_at; $i++){
                    print '<li class="page-item"><a class="page-link" href="?page='.$i.'">'.str_pad($i, 2, '0', STR_PAD_LEFT).'</a></li>';
                }

                print '<li class="page-item"><a class="page-link" href="?page='.$start_at.'&forward=ahead">Forward</a></li>';
                print '</ul>
            </nav>
            </div>
        <footer>';
   }

//    public function search_by_term_id($termid=0){
//     $sql = "SELECT * 
//     FROM `wp_wc_product_meta_lookup`
//     JOIN `wp_wc_product_attributes_lookup` 
//     ON `wp_wc_product_attributes_lookup`.`product_id` = `wp_wc_product_attributes_lookup`.`product_id`
//     WHERE `wp_wc_product_attributes_lookup`.`term_id` = 1516";
//     $sth = $this->conn->prepare($sql);
//     $sth->execute();
//     $result = $sth->fetchAll();
//     if($result){
//         return count($result);
//     }
//    }


   public function search_by_term_id($termid=0, $page=1){
    $sql = "SELECT `wp_posts`.`post_title`, `wp_posts`.`ID`
    FROM `wp_term_relationships`
    JOIN `wp_posts`
    ON `wp_term_relationships`.`object_id` = `wp_posts`.`ID`
    WHERE `wp_term_relationships`.`term_taxonomy_id` = :TERMID";
    $sth = $this->conn->prepare($sql);
    $sth->bindValue(":TERMID", $termid, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetchAll();
    if($result){
        // send upstairs
        $this->return_products_by_term = $result;
        return count($this->return_products_by_term);
    }
   }

   public function search_by_term_and_page_number($termid=0, $page=0){
       // get the product start-at value
       $this->page = $page;
       $this->termid = $termid;
    $sql = "SELECT `wp_posts`.`post_title`, `wp_posts`.`ID`
    FROM `wp_term_relationships`
    JOIN `wp_posts`
    ON `wp_term_relationships`.`object_id` = `wp_posts`.`ID`
    WHERE `wp_term_relationships`.`term_taxonomy_id` = :TERMID";
    $sth = $this->conn->prepare($sql);
    $sth->bindValue(":TERMID", $termid, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetchAll();
    if($result){
        // send upstairs
        $this->return_products_by_term = $result;
       // return count($this->return_products_by_term);
    }
   }


public function get_category_name($cat_id=1574){
    $sql = "SELECT `wp_terms`.`name` FROM `wp_terms` WHERE `term_id` = :CATID";
    $sth = $this->conn->prepare($sql);
    $sth->bindValue(":CATID", $cat_id, PDO::PARAM_INT);
    $sth->execute();
    $category_name = $sth->fetchColumn();
    return $category_name;
}


//    public function return_products_by_term(){
//     $return_products_by_term = $this->return_products_by_term;
//     $count = count($return_products_by_term);

//     $this->pagination_array($return_products_by_term, $page = 1);

//     if($count > 0){
//         $number_of_pages = $count/30;
//         $number_of_pages = ceil($number_of_pages);
//         print('<p> Number of pages '.$number_of_pages.'</p>');
//         print('<p>--------------------------------------------</p>');
//     }

//        if($this->return_products_by_term){
//            for($i=0; $i < $count; $i++){
//             print('<p>['.$i.'] &nbsp;'.$return_products_by_term[$i]['post_title'].'</p>');
//            }
//        }
//    }

   public function return_products_by_term_with_page_number($termid="0", $page="0"){
    $return_products_by_term = $this->return_products_by_term;
    $count = count($return_products_by_term);
    print('<p> Term ID:  '.$this->termid.'</p>');
   // print('<p> Start at Row:  '.$this->start_at_row.'</p>');
    print('<p> Count '.$count.'</p>');
   // $this->pagination_array($return_products_by_term, $page = 1);
   if(isset($_GET['page'])){
    $page = $_GET['page'];
    $this->page =  $page;
   }
   print('<p> Page '.$page.'</p>');

    if($count > 0){
        $number_of_pages = $count/30;
        $number_of_pages = ceil($number_of_pages);
        print('<p> Number of pages '.$number_of_pages.'</p>');
        print('<ul id="pagination_ul">');
        for($i=1;$i <= $number_of_pages; $i++){
            print('<li><a href="search.php?termid='.$this->termid.'&page='.$i.'">'.$i.'</a></li>');
        }
        print('</ul>');
        print('<br class="clear_both"/>');
        print('<p>--------------------------------------------</p>');
    }

        
        if($this->return_products_by_term){
            $count = count($this->return_products_by_term);
            $start_at_row = (int)$this->page * 30;
            $start_at_row =  $start_at_row-30;
            $end_at_row = $start_at_row + 30;
            // dont print rows that do not exist
            // if remaining page has less than 30
            // use the count() as $end_at_row
            if($end_at_row > $count){
                $end_at_row = $count;
            }
            for($i=$start_at_row; $i < $end_at_row ; $i++){
             print('<p>['.$i.'] &nbsp;<a href="#">'.$return_products_by_term[$i]['post_title'].'</a></p>');
            }
        }
        
   }


}
?>