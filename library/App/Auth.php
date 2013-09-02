<?php
/**
 * Class Helper Auth
 * @author Kanwil
 */

class App_Auth
{
	/**
	 * Mengembalikan TRUE jika user yang sedang login boleh mengakses route tertentu
	 * cache privilege untuk performa -> life time per request
	 * @param string $mod nama module
	 * @param string $con nama controller
	 * @param string $act nama action
	 * @return bool
	 */
	public static function isAllowed($mod, $con, $act) 
	{
		// cek keberadaan cache
		if (!Zend_Registry::isRegistered('privilege-cache')) {
			$pivotTable = new Zend_Db_Table('user.roles_privileges');
			// grab all available privileges 
			$query = $pivotTable->select()
				->setIntegrityCheck(false)
				->from(array('rp' => 'user.roles_privileges'), array())
				->join(array('p' => 'user.privileges'), 'rp.privilege_id = p.id', array('module', 'controller', 'actions'))
			;
			// modif query tergantung sudah login/belum
			if (!Zend_Auth::getInstance()->hasIdentity()) {
				// kalau belum login gunakan privilege milik "guest"
				$query->join(array('r' => 'user.roles'), 'rp.role_id = r.id', array('name'));
				$query->where('upper(r.name) = ?', 'GUEST');
			} else {
				$identity = Zend_Auth::getInstance()->getStorage()->read();
				$query->where('role_id = ?', $identity->role_id);
			}

			$raw = $pivotTable->fetchAll($query);
			$cache = array();
			foreach ($raw as $row) {
				if (!isset($cache[$row->module][$row->controller])) {
					$cache[$row->module][$row->controller] = array();
				}
				if ($row->actions == '%') {
					$cache[$row->module][$row->controller][] = '%';
				} else {
					$cache[$row->module][$row->controller] = array_merge($cache[$row->module][$row->controller], explode(',', $row->actions));
				}
			}
			Zend_Registry::set('privilege-cache', $cache);
		}

		$privileges = Zend_Registry::get('privilege-cache');

		if (isset($privileges[$mod][$con])) {
			$actions = $privileges[$mod][$con];
			return (in_array('%', $actions) || in_array($act, $actions));
		} else {
			return false;
		}
	}
}