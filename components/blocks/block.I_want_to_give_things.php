<?php
/**
 * @package   CherryTea.org
 * @category  themes
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
use
	cs\modules\Content\Content;
echo h::{'div.cherrytea-i-want-to-give-things-side-block[data-cs-content=form]'}(
	Content::instance()->get('form')['content']
);
