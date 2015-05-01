<?php
/**
 * @package   Form
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
namespace cs\modules\Form;

if (!Form::instance()->add(
	$_POST['name'],
	$_POST['phone'],
	$_POST['email'],
	$_POST['city'],
	$_POST['address'],
	$_POST['comment']
)
) {
	error_code(500);
}
