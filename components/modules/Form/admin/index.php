<?php
/**
 * @package   Form
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
namespace cs\modules\Form;
use
	h,
	cs\Index,
	cs\Language;

$Index          = Index::instance();
$Index->buttons = false;
$L              = Language::instance();
$Form           = Form::instance();
if (isset($_POST['delete'])) {
	$Form->del($_POST['delete']);
	$Index->save(true);
}
$all = $Form->get_all();
if (isset($_GET['csv'])) {
	interface_off();
	$Index->form = false;
	$Index->content(
		implode(
			"\n",
			array_map(
				function ($item) use ($L) {
					unset($item['id']);
					$item['date'] = $L->to_locale(date($L->_datetime_long, $item['date']));
					return '"'.implode('";"', $item).'"';
				},
				$all
			)
		)
	);
	_header('Content-disposition: attachment; filename=cherrytea.csv');
	_header('Content-Type', 'text/csv');
	return;
}
$Index->content(
	h::{'p a.uk-button.uk-button-primary[href=admin/Form?csv]'}(
		'Завантажити як CSV'
	).
	h::{'table.cs-table[hover][list][with-header]'}(
		h::{'tr.cs-table-row th.cs-table-cell'}(
			'Ім’я',
			'Дата',
			'Телефон',
			'Email',
			'Місто',
			'Адреса',
			'Коментар',
			'Видалити'
		).
		h::{'tr.cs-table-row| td.cs-table-cell'}(
			array_map(
				function ($item) use ($L) {
					return [
						$item['name'],
						$L->to_locale(date($L->_datetime_long, $item['date'])),
						$item['phone'],
						$item['email'],
						$item['city'],
						$item['address'],
						$item['comment'],
						h::{'button.uk-button.uk-button-danger[name=delete][type=submit]'}(
							h::icon('times'),
							[
								'value' => $item['id']
							]
						)
					];
				},
				$all
			)
		)
	)
);
