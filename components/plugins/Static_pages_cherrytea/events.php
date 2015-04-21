<?php
/**
 * @package   Blog_cherrytea
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
use
	cs\Event,
	cs\Page,
	cs\Route,
	cs\modules\Static_pages\Static_pages;
Event::instance()
	->on(
		'System/Index/construct',
		function () {
			if (admin_path() || current_module() !== 'Static_pages') {
				return;
			}
			$static_page = Static_pages::instance()->get(
				Route::instance()->ids[0]
			);
			$Page = Page::instance();
			$Page->Top .= h::{'cherrytea-title h1'}(
				$static_page['title']
			);
		}
	);
