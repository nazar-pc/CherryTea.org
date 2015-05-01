###*
 * @package   CherryTea.org
 * @category  themes
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
###
$ ->
	$(@querySelector('.cherrytea-i-want-to-give-things-side-block form')).submit ->
		cs.form.send(@)
		return false
