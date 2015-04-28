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
	cs\modules\Blogs\Posts;
Event::instance()
	->on(
		'Blogs/latest_posts',
		function () {
			$Page = Page::instance();
			$Page->Top .= h::{'cherrytea-title h1'}(
				'Блог'
			);
		}
	)
	->on(
		'Blogs/section',
		function () {
			$Page = Page::instance();
			$Page->Top .= h::{'cherrytea-title h1'}(
				'Блог'
			);
		}
	)
	->on(
		'Blogs/tag',
		function () {
			$Page = Page::instance();
			$Page->Top .= h::{'cherrytea-title h1'}(
				'Блог'
			);
		}
	)
	->on(
		'Blogs/post',
		function () {
			$path = Route::instance()->path;
			$path = array_pop($path);
			$id   = explode(':', $path);
			$id   = array_pop($id);
			$post = Posts::instance()->get($id);
			if (!$post) {
				return;
			}
			$Page = Page::instance();
			$Page->Top .= h::{'cherrytea-title h1'}(
				$post['title']
			);
		}
	)
	->on(
		'System/Page/pre_display',
		function () {
			Page::instance()->post_Body .= "<script src=\"//yastatic.net/share/share.js\"></script>\n";
		}
	);
