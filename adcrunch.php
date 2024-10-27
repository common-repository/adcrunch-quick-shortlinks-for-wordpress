<?php
/*
Plugin Name: adcrun.ch WordPress Plugin
Plugin URI: http://adcrun.ch
Description: Monetize your site with <a href="http://adcrun.ch">adcrun.ch</a>, this plugin can convert your links to adcrun.ch, no need to do it manually.
Version: 1.0
Author: adcrun.ch
Author URI: http://adcrun.ch
License: GPL2
*/
/*
adcrun.ch WordPress Plugin


Options:

- Enable adcrun.ch
- Convert outgoing links only/all links to adcrun.ch
- Ad type: Intestitial or banner
*/

add_action('wp_footer', 'wp_adcrunch_get_script');

//get options
function wp_adcrunch_get_options(){
    $explode = explode('/',get_option('home'));
    $options = array(
        'wp_adcrunch_id' => get_option('wp_adcrunch_id'),
        'wp_adcrunch_type' => get_option('wp_adcrunch_type'),
		'wp_adcrunch_domains' => get_option('wp_adcrunch_domains'),
        'wp_adcrunch_convert' => (get_option('wp_adcrunch_convert') == 'outgoing') ? $explode[2]:''
    );
    return $options;
}

function wp_adcrunch_get_script(){
    if(!get_option('wp_adcrunch_enable')){
        return false;
    }
    //get plugin options
    $options = wp_adcrunch_get_options();
	
	if  (get_option('wp_adcrunch_convert') == 'exclude') {
	 //populate script;
    $script = "<script>\n";
    $script .= "var accountID = ".$options['wp_adcrunch_id'].";\n";
    $script .= "var adType = '".$options['wp_adcrunch_type']."';\n";
    $script .= "var disallowDomains = [".$options['wp_adcrunch_domains']."];\n";
    $script .= "</script>\n";
    $script .= "<script src=\"http://adcrun.ch/js/fp.js\"></script>\n";
	} 
	else if  (get_option('wp_adcrunch_convert') == 'include') {
	 //populate script;
    $script = "<script>\n";
    $script .= "var accountID = ".$options['wp_adcrunch_id'].";\n";
    $script .= "var adType = '".$options['wp_adcrunch_type']."';\n";
    $script .= "var allowDomains = [".$options['wp_adcrunch_domains']."];\n";
    $script .= "</script>\n";
    $script .= "<script src=\"http://adcrun.ch/js/fp.js\"></script>\n";
	} else {
    //populate script;
    $script = "<script>\n";
    $script .= "var accountID = ".$options['wp_adcrunch_id'].";\n";
    $script .= "var adType = '".$options['wp_adcrunch_type']."';\n";
    $script .= "var disallowDomains = ['".$options['wp_adcrunch_convert']."'];\n";
    $script .= "</script>\n";
    $script .= "<script src=\"http://adcrun.ch/js/fp.js\"></script>\n";
	}
    echo $script;
		
}

//Let's create the options menu
// create custom plugin settings menu
add_action('admin_menu', 'wp_adcrunch_create_menu');

function wp_adcrunch_create_menu() {

	//create new top-level menu
	add_options_page('adcrun.ch Plugin Settings', 'adcrun.ch Settings', 'administrator', __FILE__, 'wp_adcrunch_settings_page',plugins_url('http://adcrun.ch/images/Home-32.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'wp_adcrunch_register_mysettings' );
}


function wp_adcrunch_register_mysettings() {
	//register our settings
	register_setting( 'wp-adcrunch-settings-group', 'wp_adcrunch_enable' );
	register_setting( 'wp-adcrunch-settings-group', 'wp_adcrunch_id' );
	register_setting( 'wp-adcrunch-settings-group', 'wp_adcrunch_convert' );
	register_setting( 'wp-adcrunch-settings-group', 'wp_adcrunch_domains' );
	register_setting( 'wp-adcrunch-settings-group', 'wp_adcrunch_type' );
}

function wp_adcrunch_settings_page() {
?>
<div class="wrap" style="font-family: ubuntu,Sans-Serif">

<h2>adcrun.ch WordPress Plugin</h2>
<div style = "background: #333; background: -moz-linear-gradient(top, #333, #111);
	background: -ms-linear-gradient(#333, #111);
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#333), to(#111));
	background: -webkit-linear-gradient(#333, #111);
	background: -o-linear-gradient(#333, #111);
	background: linear-gradient(#333, #111);
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	border-radius: 2px;">
	<a href="http://adcrun.ch">
		<center>
			<img src="http://adcrun.ch/images/Home-32.png" title="adcrun.ch | Earn Money From Your Website's Content"/>
		</center>
	</a>
</div>
<form method="post" action="options.php">
    <?php settings_fields( 'wp-adcrunch-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Enable Plugin</th>
        <td><input type="checkbox" <?php if( get_option('wp_adcrunch_enable' ) == 1){ echo 'checked'; }; ?> value="1" name="wp_adcrunch_enable"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">adcrun.ch ID
        <td><input type="text" name="wp_adcrunch_id" value="<?php echo get_option('wp_adcrunch_id'); ?>" /> <br/>(to get your ID, login to adcrun.ch, then go to <a href="http://adcrun.ch/referrals.php">http://adcrun.ch/referrals.php</a> find ?r=xxxx. xxxx is your adcrun.ch id)</th>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Convert</th>
        <td>
            <select name="wp_adcrunch_convert" id="convert">
                <option value="outgoing" <?php if(get_option('wp_adcrunch_convert') == 'outgoing') { echo 'selected="selected"';}?>>Outgoing Links Only</options>
                <option value="all" <?php if(get_option('wp_adcrunch_convert') == 'all') { echo 'selected="selected"';}?>>All Links</options>
				<option value="include" <?php if(get_option('wp_adcrunch_convert') == 'include') { echo 'selected="selected"';}?>>Include List of Domains</options>
				<option value="exclude" <?php if(get_option('wp_adcrunch_convert') == 'exclude') { echo 'selected="selected"';}?>>Exclude List of Domains</options>
			</select>
			<br/>
			<input type="text" name="wp_adcrunch_domains" value="<?php echo get_option('wp_adcrunch_domains'); ?>" /> <br/>(List of domains to exclude or include, use single quotes for each domain without the "www." and separate domains using a ','. For example, 'google.com' , 'facebook.com')
		</td>
        </tr>

        <tr valign="top">
        <th scope="row">Ad Type</th>
        <td>
            <select name="wp_adcrunch_type">
                <option value="int" <?php if(get_option('wp_adcrunch_type') == 'int') { echo 'selected="selected"';}?>>Interstitial Ad</options>
                <option value="banner" <?php if(get_option('wp_adcrunch_type') == 'banner') { echo 'selected="selected"';}?>>Banner Ad</options>
            </select>
			
        </td>
        </tr>

    </table>

    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

    <p>
        Feedback, bug report, and suggestions are greatly appreciated. Please submit any concerns to <a href="http://adcrun.ch">adcrun.ch</a>.
    </p>

</form>
</div>

<?php } ?>