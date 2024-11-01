<?php

include '_safe.php';

// handles settings UI

add_action('plugins_loaded', 'wishfinity_plugins_loaded');

function wishfinity_plugins_loaded()
{
	add_action('admin_menu', 'wishfinity_admin_menu_hook');
}

// add page in admin menu
function wishfinity_admin_menu_hook()
{
   add_menu_page(
		__('Wishfinity', 'wishfinity'),
		__('Wishfinity', 'wishfinity'),
		'manage_options',
		'wishfinity_settings',
		'wishfinity_settings_page_hook',
    plugin_dir_url(__DIR__). 'images/wf-icon-new-16x16.png'
	);

}


// show settings page, save settings
function wishfinity_settings_page_hook()
{
  $cfg = new WishfinitySettingsStorage;
  $cfg->load();

  $nonce_name = 'wishfinity_save_settings_nonce';
  $action = 'wishfinity_save_settings';

  if (isset($_POST['hidden_action']) && $_POST['hidden_action'] == $action)
  {

    if (!isset($_POST[$nonce_name])
			|| !wp_verify_nonce($_POST[$nonce_name], $action))
    {
      exit("Invalid nonce");
    }

    $cfg->loadFromPost();
    $cfg->save();

    wp_redirect(admin_url('admin.php?page=wishfinity_settings&success=1'));
    exit();
  }

  include WISHFINITY_PLUGIN_DIR . '/templates/settings-tpl.php';
}
