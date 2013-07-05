<?php
function woofi_admin_menu_setup(){
	add_submenu_page(
		'woocommerce',
		'Frontend Inventory Settings',
		'Frontend Inventory',
		'manage_options',
		'woofi',
		'woofi_admin_page_screen'
	);
}
add_action('admin_menu', 'woofi_admin_menu_setup');

function woofi_admin_page_screen() {
	global $submenu;
	$page_data = array();
	foreach($submenu['woocommerce'] as $i => $menu_item) {
		if($submenu['woocommerce'][$i][2] == 'woofi')
		$page_data = $submenu['woocommerce'][$i];
	}
	// output 
	?><div class="wrap">
		<?php screen_icon();?>
		<h2><?php echo $page_data[3];?></h2>
		<?php settings_errors(); ?>
		<form id="woofi_options" action="options.php" method="post">
			<?php
			settings_fields('woofi_options');
			do_settings_sections('woofi'); 
			submit_button('Save options', 'primary', 'woofi_options_submit');
			?>
		</form>
	</div><?php
}

function woofi_settings_link($actions, $file) {
	if(false !== strpos($file, 'woocommerce-frontend-inventory'))
	$actions['settings'] = '<a href="admin.php?page=woofi">Settings</a>';
	return $actions; 
}
add_filter('plugin_action_links', 'woofi_settings_link', 2, 2);

function woofi_settings_init(){
	register_setting(
	 'woofi_options',
	 'woofi_options',
	 'woofi_options_validate'
	 );

	add_settings_section(
	 'woofi_errormessage',
	 'Error Message', 
	 'woofi_errormessage_desc',
	 'woofi'
	 );

	add_settings_field(
	 'woofi_errormessage_template',
	 'Message', 
	 'woofi_errormessage_field',
	 'woofi',
	 'woofi_errormessage'
	 );
}
add_action('admin_init', 'woofi_settings_init');

function woofi_options_validate($input){
	global $allowedposttags, $allowedrichhtml;
	if(isset($input['errormessage_template']))
	$input['errormessage_template'] = wp_kses_post($input['errormessage_template']);
	return $input;
}

function woofi_errormessage_desc(){
	echo "<p>Enter the error message that you want to show to unauthorized visitors that reach the page. HTML is supported.</p>";
}

function woofi_errormessage_field() {
	$options = get_option('woofi_options');
	$errormessage = (!isset($options['errormessage_template']) || $options['errormessage_template']=="") ? 'Sorry you cannot access here!' : $options['errormessage_template'];
	$errormessage = esc_textarea($errormessage); //sanitise output
	?><textarea id="errormessage_template" name="woofi_options[errormessage_template]" cols="50" rows="5" class="code"><?php echo $errormessage;?></textarea><?php
}
?>