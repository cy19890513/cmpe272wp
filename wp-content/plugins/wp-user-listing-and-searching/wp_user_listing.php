<?php
/*
Plugin Name: WP User Listing and Searching
Description: You can create wordpress user listing on front page. A search functionality will give your visitor more comfort in finding any user.Visitor can also see more information by click on Visit info link and popup will show with admin selected fields. Short code: [wp_user_searching]
Version: 1.0
Author: Avinash
Author URI: http://www.iwebdeeds.com
**/ 

function wp_user_searching()
{
    global $wpdb;
    add_thickbox(); 
?>
 <form method="post" action="<?php
    echo get_permalink();
?>">
<table>
    <tr>
        <td><input type="text" name="search_name" value="<?php echo $_POST['search_name'];?>" style="width:100%;" placeholder="enter search term" /></td>
    
        
    </tr>
    <tr>
        <td   align="center"><input type="submit" value="Search" name="search_btn" style="width:100%;"></td>
    </tr>
</table>
 </form><?php
    
    require_once('paging.class.php');
    
    $paging = new paging;
    
    $SQL = "SELECT distinct(wp_users.ID) as ID,display_name FROM wp_users";
    
    if (isset($_POST['search_name']) && trim($_POST['search_name']) != '') {
        $SQL .= " INNER JOIN wp_usermeta ON (wp_users.ID = wp_usermeta.user_id) 
   INNER JOIN wp_usermeta AS mt1 ON (wp_users.ID = mt1.user_id) ";
    }
    
    
    $SQL .= " INNER JOIN wp_usermeta AS mt6 ON (wp_users.ID = mt6.user_id) WHERE 1=1 ";
    
    if (isset($_POST['search_name']) && trim($_POST['search_name']) != '') {
        
        $SQL .= "AND  
    (
        (wp_usermeta.meta_key = 'first_name' AND CAST(wp_usermeta.meta_value AS CHAR) LIKE '%" . $_POST['search_name'] . "%') 
        OR
        (mt1.meta_key = 'last_name' AND CAST(mt1.meta_value AS CHAR) LIKE '%" . $_POST['search_name'] . "%')
    ) ";
    }
     
    
    $TSQL            = $SQL . " AND 
        (mt6.meta_key = 'wp_capabilities' AND CAST(mt6.meta_value AS CHAR) LIKE '%Subscriber%') 
      ORDER BY display_name ASC ";
    $t_record        = $wpdb->get_results($SQL);
    $total_records   = count($t_record);
    $record_per_page = get_option('wp_user_plugin_record_per_page');
    $paging->assign(get_permalink(), $total_records, $record_per_page);
    $sql_limit = $paging->sql_limit();
    
    $SQL .= " AND 
        (mt6.meta_key = 'wp_capabilities' AND CAST(mt6.meta_value AS CHAR) LIKE '%Subscriber%') 
      ORDER BY display_name ASC LIMIT " . $sql_limit;
    
    
    $fivesdrafts = $wpdb->get_results($SQL);
    
    echo '<div class="author-entry">';
    if ($fivesdrafts) {
        
         
        
        foreach ($fivesdrafts as $author) {
             
            $author_info = get_userdata($author->ID);
?>
        <table width="300"><tr>
    <td width="100px" rowspan="4"><?php
            echo get_avatar($author->ID, 100);
?></td></tr><tr>
    <td width="200"><?php
            echo $author_info->first_name;
?> <?php
            echo $author_info->last_name;
?></td></tr><tr>
    <td width="200"><a href="mailto:<?php
            echo $author_info->user_email;
?>"><?php
            echo $author_info->user_email;
?></a></td></tr><tr>
    <td width="200">
    <div id="user_full_info_<?php echo $i;?>" style="display:none;">
     <p>
          <table width="350">
            <tr valign="top">
    <td width="50px"  valign="top" ><?php
            echo get_avatar($author->ID, 150);
?></td>
<td>
    <table>
 
<?php 
$meta_arr=array();
  $all_meta_for_user = array_map( function( $a ){ return $a[0]; }, get_user_meta( $author->ID ) );
 
  $meta_arr=unserialize(get_option('wp_user_meta_values'));
  
  if($meta_arr):
  foreach($all_meta_for_user as $key=>$udata){
    if(in_array($key, $meta_arr)){ 
?>
<tr>
    <td width="300"><?php echo ucfirst(str_replace('_', ' ',$key)).' : '.$udata;
?></td></tr>
<?php }
}
else:
    print "User information is private.";
endif;
?>
</table>
</td></tr>
 
</table>
     </p>
</div>
<a href="#TB_inline?width=400&height=550&inlineId=user_full_info_<?php echo $i;?>" class="thickbox">View full info!</a>
</td></tr> 
         
</table>
        <?php
        }
?>
    <style type="text/css">
<!--
.pages_div a { border: 1px solid #003399; background: #6699CC; color: #FFFFFF; font-family: Arial, Helvetica, sans-serif; font-size: 11px; padding: 2px 4px; text-decoration: none; font-weight: bold; }

.pages_div span { border: 1px solid #777777; background: #CCCCCC; color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 11px; padding: 2px 4px; text-decoration: none; font-weight: bold; }
-->
</style>

<div class="pages_div">

<?php
        echo $paging->fetch();
?>
</div>
<?php
    } else {
?>
    <h2>Not Record Found</h2>
    <?php
    }
    echo '</div>';
    
    
}
add_shortcode('wp_user_searching', 'wp_user_searching');

add_action('admin_menu', 'wp_user_menu');

function wp_user_menu() {
    add_options_page('WP User Setting', 'WP User Setting', 'manage_options', 'wp_user_menu', 'wp_user_menu_page');
}
function wp_user_menu_page(){
    if(isset($_POST['submit'])){
        $wp_user_meta_values= serialize($_POST['meta_option']);
        update_option('wp_user_meta_values',$wp_user_meta_values);
        update_option('wp_user_plugin_record_per_page',$_POST['record_per_page']);
    }
    ?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2>WP User Plugin Settings</h2><br>
<form method="post" action="">
    <table class="form-table"><tr><th>Records Per Page:</th><td><input type="text" name="record_per_page" value="<?php echo get_option('wp_user_plugin_record_per_page')?>"></td></tr></table>


Select options from below list which you want to show at user listing:


<table class="form-table">

    <?php 
  $all_meta_for_user = array_map( function( $a ){ return $a[0]; }, get_user_meta( 1 ) );
  $meta_arr=unserialize(get_option('wp_user_meta_values'));
  
  foreach($all_meta_for_user as $key=>$udata){
?>
<tr valign="top">
<th scope="row"><?php echo ucfirst(str_replace('_', ' ',$key))?></th>
<td>  
<input name="meta_option[]" type="checkbox" id="<?php echo $key;?>" <?php if(@in_array($key, $meta_arr)){ echo "checked='checked'";}?> value="<?php echo $key;?>"></td>
</tr>
  
<?php }?>



</table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
</form>
</div>
    <?php
}
