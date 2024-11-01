"use strict";

// embeds badge for products and products lists pages
jQuery(document).ready(function()
{
  var $ = jQuery;


  if (!isPageProduct() && !isPageCollection())
  {
    return;
  }

  var wf_params = wishfinity_data;

  console.log(wf_params);

  console.log("init js", !1*wf_params.badge_enabled_product);

  if (!1*wf_params.badge_enabled && !1*wf_params.badge_enabled_product)
  {
    return;
  }

  var badge_position = wf_params.badge_position;
  var force_center = false;
  var badge_alignment = wf_params.badge_alignment;

  if (isPageCollection() && 1*wf_params.badge_enabled)
  {

    var badge_code_collections = wf_params.badge_code_collections;
    var $ps = $('.products .product');

    $ps.each(function(k, el)
    {

      var $el = $(el);

      var url = $el.find('.woocommerce-LoopProduct-link, woocommerce-loop-product__link').attr('href');
      url = canonicalize(url);
      var badge_code = replaceAll("__URL__", url, badge_code_collections);

      var $div = $("<div data-wishfinity></div>");
      $div.append(badge_code);
      $div.css('text-align', 'center');
      $div.css('margin-top', '5px');
      //$div.css('align-self', 'center');
      $div.find('img').css('margin', '0 auto');

      $el.append($div);

    });

    return;
  }

  if (wf_params.debug)
  {
    console.log('debug mode');
    console.log(wf_params);
  }

  if (wf_params.badge_enabled_product === '0') {
    return;
  } else {
    var $div = $('[data-wishfinity]');

    if (0 == $div.length)
    {
      var $append_to;

      if ('near_title' == badge_position)
      {
        $append_to = $('h1');

        if (0 == $append_to.length || '' == $append_to.text().trim()
              || $append_to.attr('class').indexOf('logo') != -1)
        {
          //console.log("no h1, try grid")
          //$append_to = $('ul.grid').prev();
        }

        if (0 == $append_to.length)
        {
          //console.log('cant find grid');
          return;
        }

      }
      else if ('near_buy_button' == badge_position)
      {
        $append_to = $('form.cart');

        if (0 == $append_to.length)
        {
          console.log('try product meta');
          $append_to = $('.product_meta').first();
        }

      }
      else
      {
        return;
      }

      $append_to.append("<div data-wishfinity></div>");
    }

    var $div_sure = $('[data-wishfinity]');

    if (0 == $div_sure.length)
    {
      return;
    }

    if ('near_title' != badge_position || force_center)
    {
      $div_sure.css('text-align', 'center');
      $div_sure.css('flex-basis', '100%');

      $div_sure.css('margin-top', '10px');
      $div_sure.css('margin-bottom', '10px');
    }

    if (wf_params.badge_alignment) {
      $div_sure.css('text-align', wf_params.badge_alignment);
    }

  }

  var code = wf_params.badge_code;

  var url = location.href;
  code = replaceAll("__URL__", url, code);
  $div_sure.append(code);

  return;

  function replaceAll(search, replacement, x)
  {
    var target = x;
  	return target.split(search).join(replacement);
  };

	function isPageProduct()
	{
	  return wishfinity_data.is_product;
	}

	function isPageCollection()
	{
		return wishfinity_data.is_product_category;
	}

  function canonicalize(url)
  {
    var div = document.createElement('div');
    div.innerHTML = "<a></a>";
    div.firstChild.href = url; // Ensures that the href is properly escaped
    div.innerHTML = div.innerHTML; // Run the current innerHTML back through the parser
    return div.firstChild.href;
  }

});
