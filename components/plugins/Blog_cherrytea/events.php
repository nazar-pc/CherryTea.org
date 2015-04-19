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
	cs\Page;
Event::instance()
	->on(
		'Blogs/latest_posts',
		function () {
			$Page = Page::instance();
			$Page->Top .= h::{'.cherrytea-title h1'}(
				'Блог'
			);
		}
	)
	->on(
		'Blogs/section',
		function () {
			$Page = Page::instance();
			$Page->Top .= h::{'.cherrytea-title h1'}(
				'Блог'
			);
		}
	)
	->on(
		'Blogs/tag',
		function () {
			$Page = Page::instance();
			$Page->Top .= h::{'.cherrytea-title h1'}(
				'Блог'
			);
		}
	)
	->on(
		'Blogs/post',
		function () {
			$Page = Page::instance();
			$Page->Top .= h::{'.cherrytea-title h1'}(
				'Блог'
			);
		}
	);
