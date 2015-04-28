###*
 * @package   Cherrytea common
 * @category  plugins
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
###
Polymer(
	ready		: ->
		new Ya.share(
			element			: @shadowRoot.querySelector('div')
			elementStyle	:
				type		: 'none'
				quickServices	: [
					'facebook'
					'twitter'
				]
		)
);
