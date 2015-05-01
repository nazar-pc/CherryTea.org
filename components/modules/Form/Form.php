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
	cs\Config,
	cs\Mail,
	cs\CRUD,
	cs\Singleton;

/**
 * @method static Form instance($check = false)
 */
class Form {
	use
		CRUD,
		Singleton;
	protected $data_model = [
		'id'      => 'int',
		'date'    => 'int',
		'name'    => 'text',
		'phone'   => 'text',
		'email'   => 'text',
		'city'    => 'text',
		'address' => 'text',
		'comment' => 'text'
	];
	protected $table      = '[prefix]form';

	protected function cdb () {
		return Config::instance()->module('Form')->db('form');
	}
	function get_all () {
		return $this->db()->qfa(
			"SELECT *
			FROM `$this->table`
			ORDER BY `id` DESC"
		);
	}
	/**
	 * @param string $name
	 * @param string $phone
	 * @param string $email
	 * @param string $city
	 * @param string $address
	 * @param string $comment
	 *
	 * @return false|int|string
	 */
	function add ($name, $phone, $email, $city, $address, $comment) {
		$result = $this->create(
			[
				time(),
				$name,
				$phone,
				$email,
				$city,
				$address,
				$comment
			]
		);
		if ($result) {
			$Config = Config::instance();
			Mail::instance()->send_to(
				$Config->core['admin_email'],
				'Нова заявка на CherryTea.org',
				h::p(
					'Тут нова заявочка від '.xap($name),
					h::a(
						'Подивитись в адмінці',
						[
							'href' => $Config->base_url().'/admin/Form'
						]
					)
				)
			);
		}
		return $result;
	}
	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	function del ($id) {
		return $this->delete($id);
	}
}
