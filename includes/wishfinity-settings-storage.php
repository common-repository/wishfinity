<?php

include '_safe.php';

// handles settings data

class WishfinitySettingsStorage
{
  public $badge_enabled; // Home page
  public $badge_enabled_product; // Product page
  public $badge_position;
  public $badge_size;
  public $last_saved;
  public $badge_alignment;


  // export settings for JS for WooCommerce storefront
 	public function jsParams()
  {
    $ps = [];
    $ps['badge_enabled'] = 1*$this->badge_enabled;
    $ps['badge_enabled_product'] = 1*$this->badge_enabled_product;
    $ps['badge_position'] = $this->badgePositionCurrent();
    $ps['badge_alignment'] = $this->badgeCurrentAlignment();
    $ps['badge_code'] = $this->badgeCodeHtml($this->badgeSizeCurrent(), false);
    $ps['badge_code_collections'] = $this->badgeCodeHtml('Shopify-Wishfinity-Button-Small.svg', true);

    return  $ps;
  }

  // list of settings
  public function fields()
  {
    $fs = [];
    $fs[] = 'badge_enabled';
    $fs[] = 'badge_enabled_product';
    $fs[] = 'badge_position';
    $fs[] = 'badge_size';
    $fs[] = 'badge_alignment';

    return $fs;
  }

  // sanitizes data from POST request and assigns it to fields
  public function loadFromPost()
  {
    $fs = $this->fields();

    foreach($fs as $f)
    {

      if (array_key_exists($f, $_POST))
      {
        $val_sanitized = sanitize_text_field($_POST[$f]);
        $this->$f = $val_sanitized;
      }
    }
  }

  // adds prefix for option name for serializing
  public function fieldToOptionName($f)
  {
    return WISHFINITY_PLUGIN_ID.'_'.$f;
  }

  // save settings as options
  public function save()
  {

    $fs = $this->fields();
    foreach($fs as $f)
    {
      $opt_name = $this->fieldToOptionName($f);
      update_option($opt_name, $this->$f);
    }

    $f = 'last_saved';
    $opt_name = $this->fieldToOptionName($f);
    update_option($opt_name, time());
  }

  // check selected options is available / set defaults
  public function chkDefault()
  {
    $this->badge_position = $this->badgePositionCurrent();
    $this->badge_size = $this->badgeSizeCurrent();
    $this->badge_alignment = $this->badgeCurrentAlignment();
  }

  // load settings from options
  public function load()
  {
    $fs = $this->fields();
    $fs[] = 'last_saved';

    foreach($fs as $f)
    {
      $opt_name = $this->fieldToOptionName($f);
      $this->$f = get_option($opt_name, $this->$f, $default=null);
    }

    $this->chkDefault();
  }

  // lists available badge files
  public function badgeFiles()
  {
    $dir = dirname(__DIR__).'/badges';

    $files = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($dir),
      \RecursiveIteratorIterator::LEAVES_ONLY
    );

    $els = [];
    foreach ($files as $name => $file)
    {
      // Get real path for current file
      $bn = basename($name);

	    $file_path = $file->getRealPath();
	    $file_path = str_replace("\\", "/", $file_path);

      if (is_dir($file_path))
  	  {
		    continue;
	    }

      $els[] = $bn;
    }

    return $els;

  }

  // default is first of options
  public function badgePositionDefault()
  {
    $a = $this->listBadgePositions();
    $ks = array_keys($a);
    return $ks[0];
  }

  // default is first of options
  public function badgeDefaultAlignment()
  {
    $a = $this->listBadgeAlignments();
    $ks = array_keys($a);
    return $ks[0];
  }

  // default is first of options
  public function badgeSizeDefault()
  {
    $a = $this->listBadgeSizesHtml();
    $ks = array_keys($a);
    return $ks[0];
  }

  // if some previously selected badge size is not available anymore - use default
  public function badgeSizeCurrent()
  {
    $res = $this->badge_size;
    $opts = $this->listBadgeSizesHtml();

    if (!$res || empty($opts[$res]))
    {
      $res = $this->badgeSizeDefault();
    }

    return $res;
  }

  // if some previously selected badge position is not available anymore - use default
  public function badgePositionCurrent()
  {
    $res = $this->badge_position;
    $opts = $this->listBadgePositions();

    if (!$res || empty($opts[$res]))
    {
      $res = $this->badgePositionDefault();
    }

    return $res;
  }

  // if some previously selected badge alignment is not available anymore - use default
  public function badgeCurrentAlignment()
  {
    $res = $this->badge_alignment;
    $opts = $this->listBadgeAlignments();

    if (!$res || empty($opts[$res]))
    {
      $res = $this->badgeDefaultAlignment();
    }

    return $res;
  }


  // URL for badge by filename
  public function badgeUrlAbs($badge_filename)
  {
    $u = WISHFINITY_PLUGIN_URL."/badges/$badge_filename";
    return $u;
  }

  public function imageBadge($badge_filename) {
    $u_img = $this->badgeUrlAbs($badge_filename);
    $file_abs = dirname(__DIR__)."/badges/$badge_filename";
    preg_match("#viewbox=[\"']\d* \d* (\d*) (\d*)#i", file_get_contents($file_abs), $d);

    if (empty($d[1]) || empty($d[2]))
    {
      WishfinityHtml::hr("Can't get view box for $file_abs"); exit;
    }
    $w = $d[1];
    $h = $d[2];

    $img = WishfinityHtml::img($u_img, ['width'=>$w, 'height'=>$h, 'style'=>"display:block; margin-top: 10px;"]);

    return $img;
  }

  // HTML code for badge by filename
  public function badgeCodeHtml($badge_filename, $fixed_image)
  {
    $u_img = $this->badgeUrlAbs($badge_filename);

    $file_abs = dirname(__DIR__)."/badges/$badge_filename";
    preg_match("#viewbox=[\"']\d* \d* (\d*) (\d*)#i", file_get_contents($file_abs), $d);

    if (empty($d[1]) || empty($d[2]))
    {
      WishfinityHtml::hr("Can't get view box for $file_abs"); exit;
    }

    $w = $d[1];
    $h = $d[2];
    if ($fixed_image) {
      $img = WishfinityHtml::img($u_img, ['width'=>$w, 'height'=>$h]);
    } else {
      if ($this->badge_alignment == 'center') {
        $img = WishfinityHtml::img($u_img, ['width'=>$w, 'height'=>$h, 'style'=>"margin-right: auto; margin-left: auto;"]);
      } else if ($this->badge_alignment == 'right') {
        $img = WishfinityHtml::img($u_img, ['width'=>$w, 'height'=>$h, 'style'=>"margin-right: auto;"]);
      } else if ($this->badge_alignment == 'left') {
        $img = WishfinityHtml::img($u_img, ['width'=>$w, 'height'=>$h, 'style'=>"margin-left: auto;"]);
      }
    }

    $u_tpl = "https://wishfinity.com/add?url=__URL__";

    $a = WishfinityHtml::a($img, $u_tpl, ['target' => '_blank']);

    return $a;
  }

  // list of badge positions for radiolist in settings
  public function listBadgePositions()
  {
    $els = [];
    $constLabel = "Place near your product's ";
    $els['near_buy_button'] = $constLabel.'"Buy" button';
    $els['near_title'] = $constLabel."title";

    return $els;
  }

  // list of badge alignments for radiolist in settings
  public function listBadgeAlignments()
  {
    $els = [];

    $els['left'] = "Left";
    $els['center'] = "Center";
    $els['right'] = "Right";

    return $els;
  }

  // list of badge size for radiolist in settings, creates text/assigns priorities based on file names
  public function listBadgeSizesHtml()
  {

    $files = $this->badgeFiles();
    $els = [];

    foreach($files as $file)
    {
      $title = $file;
      $title = str_ireplace("Shopify-Wishfinity-Button-", '', $title);
      $title = str_ireplace(".svg", '', $title);
      $title = str_ireplace("-", ' ', $title);

      $prio = 0;

      if (stristr($title, 'small'))
      {
        $prio = 10;
      }

      if (stristr($title, 'medium'))
      {
        $prio = 20;
      }

      if (stristr($title, 'large'))
      {
        $prio = 30;
      }

      if (stristr($title, 'outline'))
      {
        $prio++;
      }

      $els[] = ['file' => $file, 'name' => "<b>$title</b><br>".$this->imageBadge($file)."<br><br>", 'prio' => $prio];

    }

    $els = WishfinityArrayHelper::multisort($els, 'prio', SORT_DESC);
    $els = WishfinityArrayHelper::map($els, 'file', 'name');

    return $els;

  }

}
