<?php

include '_safe.php';

// check if WooCoommerce active, add script for products and products lists pages
add_action('wp_enqueue_scripts', 'wishfinity_wp_enqueue_scripts');

function wishfinity_wp_enqueue_scripts()
{
  $act_plugins = apply_filters('active_plugins', get_option('active_plugins'));
  if (!in_array('woocommerce/woocommerce.php', $act_plugins))
  {
    return;
  }

  if (!is_product() && !is_product_category() && !is_shop())
  {
    return;
  }

  $cfg = new WishfinitySettingsStorage;
  $cfg->load();
  $cfg->badge_enabled *= 1;
  $cfg->badge_enabled_product *= 1;

  // if (!$cfg->badge_enabled && !$cfg->badge_enabled_product)
  // {
  //   return;
  // }

  $file_dir = WISHFINITY_PLUGIN_DIR;
  $url_dir = WISHFINITY_PLUGIN_URL;
  $js_path = 'js/wishfinity.js';

  $f_path = $file_dir.$js_path;
  $ver = filemtime($f_path).'-'.$cfg->last_saved;

  $script_id = 'wishfinity';

  wp_register_script($script_id, $url_dir.$js_path, array('jquery'), $ver, $in_footer=true);
  wp_enqueue_script($script_id);


  $script_params = $cfg->jsParams();
  $script_params['is_product'] = is_product();
  $script_params['is_product_category'] = is_product_category() || is_shop();

  wp_localize_script($script_id, $script_id.'_data', $script_params);

}
