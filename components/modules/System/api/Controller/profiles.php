<?php
/**
 * @package    CleverStyle CMS
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */
namespace cs\modules\System\api\Controller;
use
	cs\Page,
	cs\Route,
	cs\User;
trait profiles {
	static function profiles_get () {
		$User = User::instance();
		if ($User->guest()) {
			error_code(403);
			return;
		}
		$fields = [
			'id',
			'login',
			'username',
			'language',
			'timezone',
			'avatar'
		];
		$Page   = Page::instance();
		$Route  = Route::instance();
		if (isset($Route->route[1])) {
			$id     = _int(explode(',', $Route->route[1]));
			$single = count($id) == 1;
			if (
				!$User->admin() &&
				!(
				$id = array_intersect($id, $User->get_contacts())
				)
			) {
				error_code(403);
				$Page->error('User is not in your contacts');
			}
			if ($single) {
				$Page->json($User->get($fields, $id[0]));
			} else {
				$Page->json(
					array_map(
						function ($id) use ($fields, $User) {
							return $User->get($fields, $id);
						},
						$id
					)
				);
			}
		} else {
			error_code(400);
			$Page->error('Specified ids are expected');
		}
	}
}
