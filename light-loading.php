<?php
/**
 * @package light-loading
 * @version 1.0
 */
/*
Plugin Name: Light Loading
Plugin URI: http://light-loading.cdobiz.com/
Description: Adds a loading screen to all the pages of your blog
Author: Djane Rey Mabelin
Version: 1.0
Author URI: http://blog.cdobiz.com
*/

function light_loader_init(){
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false, '');
	wp_enqueue_script('jquery');
}
add_action('init','light_loader_init');


function light_loader(){

	$transparency = (get_option("light_loader_trans"))?get_option("light_loader_trans"):80;
	$top = (get_option("light_loader_top"))?get_option("light_loader_top"):20;
	$color = (get_option("light_loader_color"))?get_option("light_loader_color"):"#fff";
	$image = (get_option("light_loader_image"))?get_option("light_loader_image"):"";
	?>
	<div class="lightLoader" style="width:100%;height:100%;background-color:<?php echo $color ?>;position:fixed;top:0px; filter:alpha(opacity=<?php echo $transparency ?>);-moz-opacity:<?php echo $transparency*.01 ?>;-khtml-opacity: <?php echo $transparency*.01 ?>;opacity: <?php echo $transparency*.01 ?>;z-index:100;"></div>
	<div class="lightLoader" style="width:100%;z-index:101;position:fixed;top:<?php echo $top ?>%;text-align:center;">
		<img src="<?php echo (trim($image)!="")?$image:plugins_url()."/light-loading/loading.gif" ?>" style="filter:alpha(opacity=100);-moz-opacity:1;-khtml-opacity: 1;opacity: 1;"/>
	</div>
	<?php
}
function light_loader_scripts(){
?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".lightLoader").remove();
	});
	</script>
<?php
}
add_action('get_header','light_loader');
add_action('wp_footer','light_loader_scripts');




// mt_settings_page() displays the page content for the Test settings submenu
function light_loader_menu_page() {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $opt_name1 = 'light_loader_trans';
    $opt_name2 = 'light_loader_top';
    $opt_name3 = 'light_loader_color';
    $opt_name4 = 'light_loader_image';
	
    $hidden_field_name = 'mt_submit_hidden';
	
    $data_field_name1 = 'light_loader_trans';
    $data_field_name2 = 'light_loader_top';
    $data_field_name3 = 'mt_favorite_color';
    $data_field_name4 = 'light_loader_color';

    // Read in existing option value from database
    $opt_val1 = get_option( $opt_name1 );
    $opt_val2 = get_option( $opt_name2 );
    $opt_val3 = get_option( $opt_name3 );
    $opt_val4 = get_option( $opt_name4 );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val1 = $_POST[ $data_field_name1 ];
        $opt_val2 = $_POST[ $data_field_name2 ];
        $opt_val3 = $_POST[ $data_field_name3 ];
        $opt_val4 = $_POST[ $data_field_name4 ];

        // Save the posted value in the database
        update_option( $opt_name1, $opt_val1 );
        update_option( $opt_name2, $opt_val2 );
        update_option( $opt_name3, $opt_val3 );
        update_option( $opt_name4, $opt_val4 );

        // Put an settings updated message on the screen

?>
<div class="updated"><p><strong><?php _e('settings saved.', 'menu-light-loader' ); ?></strong></p></div>
<?php

    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Light Loader Settings', 'menu-light-loader' ) . "</h2>";

    // settings form
    
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Transparency percentage:", 'menu-light-loader' ); ?> 
<input type="text" name="<?php echo $data_field_name1; ?>" value="<?php echo $opt_val1; ?>" size="20">(default: 80)
</p>
<p><?php _e("Distance percentage from top:", 'menu-light-loader' ); ?> 
<input type="text" name="<?php echo $data_field_name2; ?>" value="<?php echo $opt_val2; ?>" size="20">(default: 20)
</p>
<p><?php _e("Background color:", 'menu-light-loader' ); ?>
<input type="text" name="<?php echo $data_field_name3; ?>" value="<?php echo $opt_val3; ?>" size="20">(default: #fff)
</p>
<p><?php _e("Loader image URL:", 'menu-light-loader' ); ?> 
<input type="text" name="<?php echo $data_field_name4; ?>" value="<?php echo $opt_val4; ?>" size="20">(default:<?php echo plugins_url()."/light-loading/loading.gif" ?>)
</p>

<hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>

<?php
 
}
add_action('admin_menu', 'light_loader_menu');
function light_loader_menu(){
	add_theme_page('Light Loader Options', 'Light Loader', 'manage_options', 'light-loader', 'light_loader_menu_page');
}