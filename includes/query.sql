




SELECT `wp_posts`.`post_title`, `wp_posts`.`ID`, `wp_postmeta`.`meta_id` 
FROM `wp_postmeta`
JOIN `wp_posts`
ON `wp_postmeta`.`post_id` = `wp_posts`.`ID`
WHERE `wp_posts`.`post_type` 
LIKE `product` OR `product_variation`