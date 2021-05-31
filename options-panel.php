<?php

// Menu creation callback
add_action('admin_menu' , 'wcaptcha_create_menu');
// Admin init callback
add_action( 'admin_init' , 'wsoum_captcha_init' );

function wsoum_captcha_init() {
    /* Register color picker script */
    wp_register_script( 'color-picker' , plugins_url( 'color-picker/js/colorpicker.js' , __FILE__ ) );  
    /* Register color picker style */
    wp_register_style( 'color-picker' , plugins_url( 'color-picker/css/colorpicker.css' , __FILE__ ) );
}

function wcaptcha_create_menu() {

	//Top-level menu
	$page = add_menu_page(__('Wsoum CAPTCHA settings' , 'wsoum-captcha') ,__('Wsoum CAPTCHA' , 'wsoum-captcha') , 'administrator' , __FILE__ , 'wcaptcha_settings' , plugins_url( 'images/icon.png' , __FILE__ ) );
    // Call required styles & scripts
    add_action('admin_print_styles-' . $page, 'wsoum_captcha_required_files');
	//Register settings
	add_action( 'admin_init' , 'register_wcaptcha_settings' );
}

function wsoum_captcha_required_files() {
    // Include required styles & scripts into head tag
    wp_enqueue_script( 'color-picker' );
    wp_enqueue_style( 'color-picker' );
}


function register_wcaptcha_settings() {
	//register our settings
	register_setting( 'wcaptcha-settings-group' , 'wcaptcha_api_key' );
	register_setting( 'wcaptcha-settings-group' , 'wcaptcha_lang' );
	register_setting( 'wcaptcha-settings-group' , 'wcaptcha_background' );
	register_setting( 'wcaptcha-settings-group' , 'wcaptcha_border' );
}

function wcaptcha_settings() {
?>
<div class="wrap">
<h2><?php _e("Wsoum CAPTCHA settings" , 'wsoum-captcha') ?></h2>
<style type="text/css">
.field {min-width: 300px;padding: 5px;text-transform: uppercase;}
.link {text-decoration: none;color: #32A2D9;}
</style>
<form method="post" action="options.php">

    <?php settings_fields( 'wcaptcha-settings-group' ) ?>
    <?php do_settings_sections( 'wcaptcha_settings' ) ?>

    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e("API key" , 'wsoum-captcha') ?></th>
        <td>
        	<input class="field" type="text" name="wcaptcha_api_key" value="<?php echo get_option('wcaptcha_api_key'); ?>" />
        	<br />
        	<a class="link" target="_blank" href="https://captcha.wsoum.ml/get_key"><?php _e("Get your API key" , 'wsoum-captcha') ?></a>
        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php _e("Primary language" , 'wsoum-captcha') ?></th>
        <td>
        	<select class="field" name="wcaptcha_lang">
                <?php
                // Available captcha languages
                    $langs = array(/*__(*/'Arabic'/* , 'wsoum-captcha')*/ => 'ar',
                                   /*__(*/'English'/* , 'wsoum-captcha')*/ => 'en'
                            );
                ?>
                <?php foreach( $langs as $lang_name => $lang_key ): ?>
                <option value="<?php echo $lang_key ?>" <?php echo ( get_option( 'wcaptcha_lang' ) == $lang_key ) ? 'selected' : '' ?>><?php echo $lang_name ?></option>
                <?php endforeach; ?>
        	</select></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php _e("Background color" , 'wsoum-captcha') ?></th>
        <td>
            <input class="field" maxlength="6" type="text" id="wcaptcha_background" name="wcaptcha_background" value="<?php echo get_option('wcaptcha_background'); ?>" />
            <br /><small><?php _e("HEX (without #)" , 'wsoum-captcha') ?></small>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e("Border color" , 'wsoum-captcha') ?></th>
        <td>
            <input class="field" maxlength="6" type="text" id="wcaptcha_border" name="wcaptcha_border" value="<?php echo get_option('wcaptcha_border'); ?>" />
            <br /><small><?php _e("HEX (without #)" , 'wsoum-captcha') ?></small>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e("Demo" , 'wsoum-captcha') ?></th>
        <td>
            <script type="text/javascript"><!--
                wcaptcha_options = {language: '<?php echo get_option('wcaptcha_lang'); ?>', key: '<?php echo get_option('wcaptcha_api_key'); ?>', background: '#<?php echo get_option('wcaptcha_background'); ?>', border: '#<?php echo get_option('wcaptcha_border'); ?>'}; 
            //--></script>
            <script type="text/javascript" src="https://captcha.wsoum.ml/wcaptcha.js"></script>           
        </td>
        </tr>

        <script type="text/javascript">
            jQuery(document).ready(function(){

                jQuery('#wcaptcha_border, #wcaptcha_background').focus( function(){

                    var self = jQuery( this );

                    self.ColorPicker( {

                        color: '#' + self.val(),

                        onSubmit: function(wsm , hex , rgb , el) {
                            self.val( hex );
                            jQuery( el ).ColorPickerHide();
                        },

                        onBeforeShow: function () {
                            jQuery( this ).ColorPickerSetColor( this.value );
                        },

                        onChange: function (wsm , hex , rgb , el) {
                            self.val( hex );
                        }
                                              
                    } )

                    .bind( 'keyup' , function(){
                        jQuery( this ).ColorPickerSetColor( this.value );
                    } );                    

                } ); 
                              
            } );
        </script>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e("Save settings" , 'wsoum-captcha') ?>" />
    </p>

</form>
</div>
<?php } ?>