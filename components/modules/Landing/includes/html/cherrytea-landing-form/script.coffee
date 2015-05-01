###*
 * @package   Landing
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
###
Polymer(
	ready		: ->
		$ ->
			$(@querySelector('.cherrytea-landing-form form')).submit ->
				cs.form.send(@)
				return false
);
