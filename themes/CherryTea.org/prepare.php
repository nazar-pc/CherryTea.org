<?php
/**
 * @package   CherryTea.org
 * @category  themes
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
namespace cs;
$Page = Page::instance();
/**
 * @var _SERVER $_SERVER
 */
if (preg_match('/msie|trident/i', $_SERVER->user_agent)) {
	$Page->Head .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
}
$Page->Head .= '<meta name="viewport" content="width=1200">';
$Page->level['Content'] = home_page() ? 1 : 3;
