<div class="wrap">

  <style>
   table {
     background: white;
     width: 100%;
     border-collapse: collapse;
   }
   th {
     background: #333;
     color: white;
     font-weight: bold;
   }
   td, th {
     padding: 6px;
     border: 1px solid #ccc;
     text-align: left;
   }
   td.title {
     width: 160px;
     vertical-align: top;
   }
   button.my-btn {
     background: #ff6400 !important;
     border-color: #ff6400 !important;
   }
  </style>

	<h1 class="wp-heading-inline" style="font-weight: bold;"><?php _e("+Wishfinity / Settings / Button Customization", "wishfinity"); ?></h1>
	<hr class="wp-header-end">

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
		<div id="post-body-content" style="position: relative;">

		<?php

      $act_plugins = apply_filters('active_plugins', get_option('active_plugins'));
      if (!in_array('woocommerce/woocommerce.php', $act_plugins))
      {
        ?>
          <div class="notice update notice-error" >
	          <p><?php _e('Wishfinity plugin requires WooCommerce. Please install/activate it', 'wishfinity'); ?></p>
					</div>
				<?php
      }

			if(isset($_REQUEST['success']))
			{
				?>
					<div class="notice update notice-success" >
						<p><?php _e('Settings have been updated successfully!', 'wishfinity'); ?></p>
					</div>
				<?php
			}

      $nonce_name = 'wishfinity_save_settings_nonce';
      $action = 'wishfinity_save_settings';
	  ?>

		<form action='' method='post'>
			<?php wp_nonce_field($action, $nonce_name); ?>

			<input type="hidden" name="hidden_action" value="<?php echo $action;?>" />

      <h1 class="wp-heading-inline"><?php _e("Homepage", "wishfinity"); ?></h1>
			<table class="form-table">
				<tbody>
          <tr>
  					<td scope='row' class="title"><?php _e('Enable', 'wishfinity'); ?></td>
  					<td>
  						<?php
                $opt_name = 'badge_enabled';
  							$ch = '';
  							if ($cfg->$opt_name)
  							{
  								$ch = 'checked';
  							}
                echo "<input type='hidden' name='$opt_name' value=0 />";
  						  echo "<input type='checkbox' name='$opt_name' $ch value=1 />";
  						?>
              <label>On</label><br/>
              <?php
                echo $cfg->imageBadge('Shopify-Wishfinity-Button-Small.svg');
              ?>
  					</td>
  				</tr>
        </tbody>
      </table>
      <h1 class="wp-heading-inline"><?php _e("Product Pages", "wishfinity"); ?></h1>
      <table class="form-table">
				<tbody>
          <tr>
  					<td scope='row' class="title"><?php _e('Enable', 'wishfinity'); ?></td>
  					<td>
  						<?php
                $opt_name = 'badge_enabled_product';
  							$ch = '';
  							if ($cfg->$opt_name)
  							{
  								$ch = 'checked';
  							}
                echo "<input type='hidden' name='$opt_name' value=0 />";
  						  echo "<input type='checkbox' name='$opt_name' $ch value=1 />";
  						?>
              <label>On</label><br/>
  					</td>
  				</tr>
  	      <tr>
  					<td scope='row' class="title"><?php _e('Button position', 'wishfinity'); ?></td>
  					<td>
  						<?php
                $opt_name = 'badge_position';
                $els = $cfg->listBadgePositions();
                foreach($els as $key=>$el)
  							{
  							  $checked = '';
  								if ($key == $cfg->$opt_name)
  								{
                  	$checked = 'checked';
  								}
  								$lbl = $el;

  								echo "<input type=radio name='$opt_name' value='$key' $checked><label>$lbl</label><br>";
  							}
  						?>
  					</td>
  				</tr>
  				<tr>
  					<td scope='row' class="title"><?php _e('Button alignment', 'wishfinity'); ?></td>
  					<td>
  						<?php
                $opt_name = 'badge_alignment';
                $els = $cfg->listBadgeAlignments();
                foreach($els as $key=>$el)
  							{
  							  $checked = '';
  								if ($key == $cfg->$opt_name)
  								{
                  	$checked = 'checked';
  								}
  								$lbl = $el;

  								echo "<input type=radio name='$opt_name' value='$key' $checked><label>$lbl</label><br>";
  							}
  						?>
  					</td>
  				</tr>
  	      <tr>
  					<td scope='row' class="title"><?php _e('Button size', 'wishfinity'); ?></td>
  					<td>
  						<?php
                $opt_name = 'badge_size';
                $els = $cfg->listBadgeSizesHtml();

                foreach($els as $key=>$el)
  				  		{
  					  	  $checked = '';
  						  	if ($key == $cfg->$opt_name)
    							{
                    $checked = 'checked';
  		  					}
  								$lbl = $el;
  								echo "<div style='height: 90px;'><input type=radio name='$opt_name' value='$key' $checked><label>$lbl</label></div>";
  							}
  						?>
  					</td>
  				</tr>
			</tbody>
		</table>

  	<p class='submit'>
  		<button type='submit' class='button-primary my-btn' />
        <?php _e('Save', 'wishfinity'); ?>
      </button>
  	</p>
  </form>
</div>
</div>
</div>
</div>
