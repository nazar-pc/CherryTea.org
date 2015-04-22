<?php
/**
 * @package   CherryTea common
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
use
	cs\Event,
	cs\Page\Meta;
Event::instance()->on(
	'System/Page/pre_display',
	function () {
		Meta::instance()->image('/components/plugins/includes/img/logo.png');
	}
);
