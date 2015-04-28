###*
 * @package   Cherrytea common
 * @category  plugins
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
###
cs.cherrytea = cs.cherrytea || {}
cs.cherrytea.popup = ->
	$.cs.simple_modal(
		"<cherrytea-popup></cherrytea-popup>"
		true
		470
	)
