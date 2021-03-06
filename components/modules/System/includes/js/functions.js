// Generated by CoffeeScript 1.4.0

/**
 * @package		CleverStyle CMS
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2015, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
*/


(function() {
  var L, value_by_name;

  L = cs.Language;

  /**
   * Get value by name
   *
   * @param {string}	name
   *
   * @return {string}
  */


  value_by_name = function(name) {
    return document.getElementsByName(name).item(0).value;
  };

  /**
   * Cache cleaning
   *
   * @param 			element
   * @param {string}	action
  */


  cs.admin_cache = function(element, action, partial_path) {
    $(element).html("<div class=\"uk-progress uk-progress-striped uk-active\">\n	<div class=\"uk-progress-bar\" style=\"width:100%\"></div>\n</div>");
    $.ajax({
      url: action,
      data: {
        partial_path: partial_path
      },
      type: 'delete',
      success: function(result) {
        return $(element).html(result ? "<p class=\"uk-alert uk-alert-success\">" + L.done + "</p>" : "<p class=\"uk-alert uk-alert-danger\">" + L.error + "</p>");
      }
    });
  };

  /**
   * Send request for db connection testing
   *
   * @param {int}	index
   * @param {int}	mirror_index
  */


  cs.db_test = function(index, mirror_index) {
    var modal;
    modal = $.cs.simple_modal("<div>\n	<h3 class=\"cs-center\">" + L.test_connection + "</h3>\n	<div class=\"uk-progress uk-progress-striped uk-active\">\n		<div class=\"uk-progress-bar\" style=\"width:100%\"></div>\n	</div>\n</div>");
    return $.ajax({
      url: 'api/System/admin/databases_test',
      data: index !== void 0 ? {
        index: index,
        mirror_index: mirror_index
      } : {
        db: {
          type: value_by_name('db[type]'),
          name: value_by_name('db[name]'),
          user: value_by_name('db[user]'),
          password: value_by_name('db[password]'),
          host: value_by_name('db[host]'),
          charset: value_by_name('db[charset]')
        }
      },
      type: 'get',
      success: function(result) {
        var status;
        if (result) {
          status = 'success';
        } else {
          status = 'danger';
        }
        result = result ? L.success : L.failed;
        return modal.find('.uk-progress').replaceWith("<p class=\"cs-center uk-alert uk-alert-" + status + "\" style=text-transform:capitalize;\">" + result + "</p>");
      },
      error: function() {
        return modal.find('.uk-progress').replaceWith("<p class=\"cs-center uk-alert uk-alert-danger\" style=text-transform:capitalize;\">" + L.failed + "</p>");
      }
    });
  };

  /**
   * Send request for storage connection testing
   *
   * @param {int}	index
  */


  cs.storage_test = function(index) {
    var modal;
    modal = $.cs.simple_modal("<div>\n	<h3 class=\"cs-center\">" + L.test_connection + "</h3>\n	<div class=\"uk-progress uk-progress-striped uk-active\">\n		<div class=\"uk-progress-bar\" style=\"width:100%\"></div>\n	</div>\n</div>");
    return $.ajax({
      url: 'api/System/admin/storages_test',
      data: index !== void 0 ? {
        index: index
      } : {
        storage: {
          url: value_by_name('storage[url]'),
          host: value_by_name('storage[host]'),
          connection: value_by_name('storage[connection]'),
          user: value_by_name('storage[user]'),
          password: value_by_name('storage[password]')
        }
      },
      type: 'get',
      success: function(result) {
        var status;
        if (result) {
          status = 'success';
        } else {
          status = 'danger';
        }
        result = result ? L.success : L.failed;
        return modal.find('.uk-progress').replaceWith("<p class=\"cs-center uk-alert uk-alert-" + status + "\" style=text-transform:capitalize;\">" + result + "</p>");
      },
      error: function() {
        return modal.find('.uk-progress').replaceWith("<p class=\"cs-center uk-alert uk-alert-danger\" style=text-transform:capitalize;\">" + L.failed + "</p>");
      }
    });
  };

  /**
   * Toggling of blocks group in admin page
   *
   * @param {string}	position
  */


  cs.blocks_toggle = function(position) {
    var container, items;
    container = $("#cs-" + position + "-blocks-items");
    items = container.children('li:not(:first)');
    if (container.data('mode') === 'open') {
      items.slideUp('fast');
      container.data('mode', 'close');
    } else {
      items.slideDown('fast');
      container.data('mode', 'open');
    }
  };

  /**
   * For textarea in blocks editing
   *
   * @param item
  */


  cs.block_switch_textarea = function(item) {
    $('#cs-block-content-html, #cs-block-content-raw-html').hide();
    switch ($(item).val()) {
      case 'html':
        $('#cs-block-content-html').show();
        break;
      case 'raw_html':
        $('#cs-block-content-raw-html').show();
    }
  };

  cs.test_email_sending = function() {
    var email;
    email = prompt(L.email);
    if (email) {
      return $.ajax({
        url: 'api/System/admin/email_sending_test',
        data: {
          email: email
        },
        type: 'get',
        success: function() {
          return alert(L.done);
        },
        error: function() {
          return alert(L.test_email_sending_failed);
        }
      });
    }
  };

}).call(this);
