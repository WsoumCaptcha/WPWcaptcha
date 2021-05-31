<?php
/*
Plugin Name: Wsoum CAPTCHA
Plugin URI: https://github.com/WsoumCaptcha/WPWcaptcha
Description: a simple comment CAPTCHA protection based on Wsoum CAPTCHA API (support arabic & english)
Version: 1.0
Author: Wsoum
Author URI: https://wsoum.ml
*/

// include options page
include('options-panel.php');

// load translations
function wsoum_captcha_i18n(){
	load_plugin_textdomain( 'wsoum-captcha', false , dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

// Setup some values just after the plugin activation
function wcaptcha_activation(){

	// Set default values
	$options = array( 'wcaptcha_lang' => 'en' , 'wcaptcha_background' => 'ffffff' , 'wcaptcha_border' => 'dddddd');

	foreach($options as $option_name => $default_value ):

		if ( !get_option( $option_name ) ) update_option( $option_name , $default_value );
				
	endforeach;
}

// Test the submited captcha
function test_captcha($comment){

	// if user not logged in & the api key exist & check if the captcha field exist
	if ( /*!is_user_logged_in() &&*/ '' != get_option('wcaptcha_api_key')):
		// if the captcha field empty
		if ( empty( $_POST['wcaptcha_input'] ) ):

			wp_die( __('Please enter the verification code (CAPTCHA).' , 'wsoum-captcha') ) ;

		endif;

		// Check API : https://captcha.wsoum.ml/developers
		$response = wp_remote_retrieve_body( wp_remote_get("https://captcha.wsoum.ml/api/verify_wcaptcha.php?key={$_POST['wcaptcha_key']}&input={$_POST['wcaptcha_input']}&challenge={$_POST['wcaptcha_challenge']}&lang={$_POST['wcaptcha_language']}") );

		if ( $response == 'false' ):

			wp_die( __('the verification code (CAPTCHA) wrong.' , 'wsoum-captcha') );

		endif;


	endif;

	return $comment;
}

// Add the captcha field into the comment form
function captcha_field( $content ){
	// if user not logged in & the api key exist
	if ( /*!is_user_logged_in() &&*/ '' != get_option('wcaptcha_api_key') ):
		ob_start();
	?>

	<div id="wcaptcha">
            <script type="text/javascript"><!--
                wcaptcha_options = {language: '<?php echo get_option( 'wcaptcha_lang' ) ?>', key: '<?php echo get_option( 'wcaptcha_api_key' ) ?>', background: '#<?php echo get_option( 'wcaptcha_background' ) ?>', border: '#<?php echo get_option( 'wcaptcha_border' ) ?>'}; 
            //--></script>
            <script type="text/javascript" src="https://captcha.wsoum.ml/wcaptcha.js"></script> 		
	</div>  
	          
	<?php

		$captcha = ob_get_contents();
		ob_end_clean();
		//return the default form with captcha field
		return $content . $captcha;

	else:
		// return the default form
		return $content;

	endif;
}
// Register some hooks (plugin activation, init)
register_activation_hook( __FILE__ , 'wcaptcha_activation' );
add_action('init','wsoum_captcha_i18n');

// Add some filters (add captcha field, test captcha)
if (isset($_POST['wcaptcha_input'])) add_filter( 'preprocess_comment' , 'test_captcha' );
add_filter( 'comment_form_field_comment' , 'captcha_field' );
