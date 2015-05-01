###*
 * @package   Form
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
###
cs.form	=
	send	: (form) ->
		data = {}
		$(form).serializeArray().forEach (item) ->
			data[item.name] = item.value
		$.ajax(
			url		: 'api/Form'
			data	: data
			type	: 'post'
			success	: ->
				cs.cherrytea.popup()
				$(form).each ->
					@reset()
		)
