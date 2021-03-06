###*
 * @package   Blogs cherrytea
 * @category  plugins
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
###
Polymer(
	ready		: ->
		@super()
		date = new Date(@jsonld.date * 1000)
		@date = ('0' + date.getDate())[-2..] + '-' + ('0' + (date.getMonth() + 1))[-2..] + '-' + date.getFullYear()
);
