###*
 * @package   Blogs
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
###
Polymer(
	ready		: ->
		@post.comments_enabled		= @comments_enabled
		@post.read_more_text		= cs.Language.read_more
		$short_content				= $(@$.short_content)
		$short_content.html(
			@post.short_content.replace(/<img([^>])+>/i, '')
		)
		do (first_child = $short_content.children().first()) ->
			if first_child.is(':empty')
				first_child.remove()
		date = new Date(@post.date * 1000)
		@date = ('0' + date.getDate())[-2..] + '-' + ('0' + (date.getMonth() + 1))[-2..] + '-' + date.getFullYear()
);
