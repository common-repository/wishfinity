<?php

include '_safe.php';

// function for improve HTML tags handlnig

class WishfinityHtml
{
  // prepares general tag
  public static function tag($tag, $content = '', $opts = [])
  {
    $tag = strtolower($tag);

	  $r = '';
	  $r .= "<$tag ";

	  foreach($opts as $k=>$v)
	  {
    	$r .= " $k = '".esc_attr($v)."' ";
  	}

    if ('img' == $tag)
    {
      $r .= " />";
    }
    else
    {
  	  $r .= ">";
  	  $r .= $content;
  	  $r .= "</$tag>";
    }

    return $r;
  }

  // displays debugging notice
  public static function hr($t)
  {
    echo "<hr>";
    echo $t;
    echo "<hr>";
  }

  // prepares IMG tag
  public static function img($url, $opts = [])
  {
    $opts['src'] = $url;
    return self::tag('img', $content = '', $opts);
  }

  // prepares A tag
  public static function a($content, $url, $opts = [])
  {
    $opts['href'] = $url;
    return self::tag('a', $content, $opts);
  }
}