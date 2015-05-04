<?php
/**
 * @package   CherryTea common
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
use
	cs\Config,
	cs\Event,
	cs\Page,
	cs\Page\Meta;
Event::instance()->on(
	'System/Page/pre_display',
	function () {
		Page::instance()->post_Body .= "<script src=\"//yastatic.net/share/share.js\"></script>\n";
		Meta::instance()->image(Config::instance()->base_url().'/components/plugins/CherryTea_common/includes/img/logo.png');
	}
);
