<?php

include '_safe.php';

// handle arrays operations
class WishfinityArrayHelper
{
  // create key value map from array elements fields
  public static function map($a, $k, $v)
  {
    $res = [];

    foreach($a as $el)
    {
      $res[$el[$k]] = $el[$v];
    }

    return $res;
  }

  // allows to sort multidimension array
  // example of use array, 'field1', SORT_DESC, 'field2', SORT_ASC, ...
  public static function multisort()
  {
  	$args = func_get_args();
  	$data = array_shift($args);

  	foreach ($args as $n => $field)
  	{
  		if (is_string($field))
  		{
  			$tmp = array();
  			foreach ($data as $key => $row)
  			{
  				$tmp[$key] = $row[$field];
  			}

  			$args[$n] = $tmp;
  		}
  	}

  	$args[] = &$data;
  	call_user_func_array('array_multisort', $args);
  	return array_pop($args);
  }
}