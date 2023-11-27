/**
 * Lib : Winamaz WooCommerce Main Front JS
 * Package : Winamaz WooCommerce
 */
(function ($, Plugin) {

  "use strict";

  /**
   * Main :
   */

  $(document).ready(function () {

    /* initCompare */
    initCompare();

    /* scrollToCompare */
    scrollToCompare();

  });

  /**
   * Compare functions :
   */

  // initCompare
  function initCompare() {
    $('.winamaz-woocommerce-wrapper').each(function () {

      const product = $(this);
      const compare = product.find('.winamaz-compare');

      // Data preloading
      let preloading = false;
      if ( compare.attr('data-preloading') == 'true' ) {
        preloading = true;
        product.find('.winamaz-compare-switcher').attr('data-ajax', 'false');
        doCompare(product);
      }

      // Force display data
      if ( compare.attr('data-force') == 'true' ) {
        compare.removeClass('winamaz-closed-compare');
        if ( !preloading ) {
          doCompare(product);
        }
      }

    });
  }

  // doCompare
  function doCompare(element) {

    const keyword = element.attr('data-keyword');
    const atts = element.attr('data-atts');

    if ( !keyword || !atts ) {
      // Product unavailable
      element.remove();
      $('.winamaz-woocommerce-price').remove();
      $('.winamaz-woocommerce-notice').remove();
      $('.winamaz-woocommerce-button').remove();
    };

    const settings = {
      "action": Plugin.namespace + '-doCompare',
      "nonce": element.attr('data-nonce'),
      "keyword": keyword,
      "atts": atts
    };

    $.ajax({
      "type":'GET',
      "data":$.param(settings),
      "dataType":'JSON',
      "url":Plugin.ajaxurl,
      "timeout": Plugin.timeout,
      "cache": true,
      success: function (response) {

        if ( response.status == 'success' ) {

          // Remove loader
          element.find('.winamaz-loader-wrapper').remove();
          // Reset output
          element.find('.winamaz-compare').html('');
          // Set output
          element.find('.winamaz-compare').append(response.content.output);
          // Set bestprice
          $('.winamaz-woocommerce-price .price').html(response.content.bestprice.price + ' ' + Plugin.currency);
          // Set Link
          const link = response.content.bestprice.link;
          $('.winamaz-woocommerce-price').wrap('<a href="' + link + '" target="_blank"></a>');
          // Show compare button
          $('.winamaz-woocommerce-button').css('display','block');
          // Remove atts
          element.removeAttr('data-atts');
          // Init Globals
          initTooltip();

        } else if ( response.status == 'inqueue' ) {
          element.find('.winamaz-string').html(Plugin.strings.request.inqueue);
          setTimeout(function () {
            // Try agin
            doCompare(element);
          }, 5000); // 5s

        } else {
          // Product unavailable
          element.remove();
          $('.winamaz-woocommerce-price').remove();
          $('.winamaz-woocommerce-notice').remove();
          $('.winamaz-woocommerce-button').remove();
        }
      },
      error: function (data) {

        // Force parse response
        const response = parseResponse(data);

        if ( response ) {
          // Remove loader
          element.find('.winamaz-loader-wrapper').remove();
          // Reset output
          element.find('.winamaz-compare').html('');
          // Set output
          element.find('.winamaz-compare').append(response.content.output);
          // Set bestprice
          $('.winamaz-woocommerce-price .price').html(response.content.bestprice.price + ' ' + Plugin.currency);
          // Set Link
          const link = response.content.bestprice.link;
          $('.winamaz-woocommerce-price').wrap('<a href="' + link + '" target="_blank"></a>');
          // Show compare button
          $('.winamaz-woocommerce-button').css('display','block');
          // Remove atts
          element.removeAttr('data-atts');
          // Init Globals
          initTooltip();

        } else {
          // Request error
          element.remove();
          $('.winamaz-woocommerce-price').remove();
          $('.winamaz-woocommerce-notice').remove();
          $('.winamaz-woocommerce-button').remove();
        }
      }
    });
  }

  /**
   * Extended functions :
   */

  // scrollToCompare
  function scrollToCompare() {
    $('.winamaz-woocommerce-button').on('click', function () {
      $([document.documentElement, document.body]).animate({
        scrollTop: $('.winamaz-woocommerce-container').offset().top - 200
      }, 1000);
    });
  }

  // initTooltip
  function initTooltip() {
    $('.winamaz-tooltip:not(".tooltipstered")').tooltipster({
      maxWidth: 300,
      zIndex: 100000
    });
  }

  // parseResponse
  function parseResponse(data){
    if ( data.responseText ) {
      const match = data.responseText.match('\{.*\:\{.*\:.*\}\}');
      if ( match ) {
        const json = match[0];
        if ( json !== '' ) {
          const response = JSON.parse(json);
          if ( response && response.status == 'success' ) {
            return response;
          }
        }
      }
    }
    return false;
  }

})(jQuery, winamazwoocommercePlugin || {});