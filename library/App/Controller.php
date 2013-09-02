<?php
/**
 * Parent dari semua Controller yang dipakai project ini
 * Menyediakan authentication dan authorization
 * Menyediakan beberapa helper juga
 *
 * @author Kanwil
 */
 
class App_Controller extends Zend_Controller_Action
{

	/**
	 * Mengembalikan adapter untuk Zend_Auth yang digunakan aplikasi ini
	 * @return Zend_Auth_Adapter
	 */
	protected function _getAuthAdapter() 
	{
		$authAdapter = new Zend_Auth_Adapter_DbTable(
			Zend_Db_Table::getDefaultAdapter()
		);
		$authAdapter
			->setTableName('user.logins') // the db table where users are stored
			->setIdentityColumn('username')                     
			->setCredentialColumn('password')
			// ->setCredentialTreatment("encode(digest(?, 'sha1'), 'hex')") // perlu extensi pgcrypto
			->setCredentialTreatment("?") // harus panggil ->setCredential(sha1($password))
		;

		return $authAdapter;
	}
	
	/**
	 * Lakukan pengecekan identity (authentication): kalau ga ada, hanya boleh buka halaman login
	 * Lakukan pengecekan privilege (authorization): kalau ga cocok, redirect ke halaman home
	 */
	public function init()
	{
		// profiler (debug)
		// $profiler = Zend_Db_Table::getDefaultAdapter()->getProfiler();
		// $profiler->setEnabled(true);
		// cache metadata di direktori /data/cache
		if (APPLICATION_ENV == 'production') {
			$frontendOptions = array('automatic_serialization' => true);
			$backendOptions  = array('cache_dir' => APPLICATION_PATH.'/../data/cache');
			$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
			Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
		}
		// init helpers
		$this->_redirector = $this->_helper->getHelper('Redirector');
		// route info
		$mod = $this->_request->getModuleName();
		$con = $this->_request->getControllerName();
		$act = $this->_request->getActionName();
		
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$identity = Zend_Auth::getInstance()->getStorage()->read();

			// count unread telegrams
			$this->unreadCount($identity->id);

			// cache role
			if (!isset($identity->role)) {
				$table = new Zend_Db_Table('user.roles');
				$tableJabatan = new Zend_Db_Table('master.M_JABATAN');
				$role = $table->find($identity->role_id)->current();
                $jabatan = $tableJabatan->find($role->kogas)->current();
                $identity->role = $role->name;
                $identity->panglima = $role->panglima;

                if($role->kogas)
                {
                    $identity->kogasID = $role->kogas;
                    $identity->kogas = $jabatan->nama_jabatan;
                }

				Zend_Auth::getInstance()->getStorage()->write($identity);
			}

			// cek privilege
			if (!($mod == 'default' && $con == 'index')) { // semua /default/index/* boleh diakses
				if (!App_Auth::isAllowed($mod, $con, $act)) {
					$this->_redirector->gotoSimple('index', 'index', 'default');
				}
			}
		} else {
			// hanya boleh ke default/index/login atau default/home/index|detail
			if (!($mod == 'default' && $con == 'home' && $act == 'index')
				&&
				!($mod == 'default' && $con == 'home' && $act == 'detail')
				&&
				!($mod == 'default' && $con == 'index' && $act == 'login')
			) {
				$this->_redirector->gotoSimple('index', 'home', 'default');
			}
		}

        //terauntentikasi atau tidak tetap menampilkan data yg sama
        $this->links();
	}

    /**
     * generate tautan ke website lain
     * @author tajhul.faijin@sangkuriang.co.id
     */
    protected function links(){
        $linkModel = new Default_Model_DbTable_Link();
        $this->view->links = $linkModel->getAll();
    }

	/*
	 * Fungsionalitas yang sering dipakai
	 * @author : tajhul.faijin@sangkuriang.co.id
	 * */
	// Disable view dan layout
	protected function disableViewAndLayout()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();
	}
	// Disable view
	protected function disableView()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
	}
	// Disable layout
	protected function disableLayout()
	{
		$this->getHelper('layout')->disableLayout();
	}
	// check ajax request
	protected final function isAjax()
	{
		if ($this->_request->isXmlHttpRequest() || isset($_GET['ajax'])) return true;
		return false;
	}
	// redirect to spesific url
	protected function redirectTo($url)
	{
		if( empty($url) ) return false;
		$this->_redirect($url);
	}
	// print array with nice styles
	protected function printArray($array)
	{
		if( !is_array( $array ) || !is_object( $array )){
			echo $array;
		}

		//harus array atau object
		if(is_array($array) || is_object($array)){
			//convert ke array jika object
			if(!is_array($array)) self::objectToArray($array);

			echo '<pre>';
				echo print_r($array);
			echo '</pre>';
		}
	}

	// convert stdClass object to array
	protected function objectToArray($object) {
		$array = array();

		if (is_object($object)) {
			foreach ($object as $key => $value) {
				$array[$key] = $value;
			}
		} else {
			$array = $object;
		}

		return $array;
	}
	// set header file
	protected function setCustomHeader($key)
	{
		$contentType = array(
			'json' => 'application/json',
			'js'   => 'text/javascript',
			'html' => 'text/html',
			'xml'  => 'text/xml',
			'css'  => 'text/css',
		);
		$type = ($contentType[$key]) ? $contentType[$key] : $key;
		$this->getResponse()->setHeader('Content-Type', $type);
	}
	// untuk konversi format tanggal indonesia ke format english yyyy-mm-dd
	protected function convertDateToEn($date, $separator = '/')
	{
		if( empty($date) ) return false;

		//$date berisi tanggal dlm format indo, e.x : 27 Agustus 2011
		// menghasilkan array [0] => tanggal, [1] => bulan (dlm format indo), [2]=> tahun
		$get_date = explode($separator, $date);

		$tanggal = $get_date[0];
		$bulan = $get_date[1];
		$tahun = $get_date[2];

		if( $separator == '/' ){
			return $tahun .'-'. $bulan .'-'. $tanggal;
		} else {
			switch(strtolower($bulan)){
				case "januari" : $month = '01'; break;
				case "februari" : $month = '02'; break;
				case "maret" : $month = '03'; break;
				case "april" : $month = '04'; break;
				case "mei" : $month = '05'; break;
				case "juni" : $month = '06'; break;
				case "juli" : $month = '07'; break;
				case "agustus" : $month = '08'; break;
				case "september" : $month = '09'; break;
				case "oktober" : $month = 10; break;
				case "november" : $month = 11; break;
				case "desember" : $month = 12; break;
			}
		}

		return $tahun .'-'. $month .'-'. $tanggal;
	}
	// untuk konversi format tanggal En yyyy-mm-dd ke format indonesia
	protected function convertDateToId($date)
	{
		if( empty($date) ) return false;

		$get_date = explode("-", $date);

		$year = $get_date[0];
		$bulan = $get_date[1];
		$date = $get_date[2];

		switch($bulan){
			case 1 : $month = "Januari"; break;
			case 2 : $month = "Februari"; break;
			case 3 : $month = "Maret"; break;
			case 4 : $month = "April"; break;
			case 5 : $month = "Mei"; break;
			case 6 : $month = "Juni"; break;
			case 7 : $month = "Juli"; break;
			case 8 : $month = "Agustus"; break;
			case 9 : $month = "September"; break;
			case 10 : $month = "Oktober"; break;
			case 11 : $month = "November"; break;
			case 12 : $month = "Desember"; break;
		}
		return $date .' '. $month .' '. $year;
	}

    public function checkOpsKogas($kogasParam) {
        $kogas = array( 'kogasgabla', 'kogasgabfib', 'pasrat', 'kogasgablinud', 'kogasgabrat', 'kogasud', 'kogasgabratmin', 'kogasgabhantai');
        return in_array(strtolower($kogasParam), $kogas);
    }
    /** menghitung telegram yang belum dibaca  */
    public function unreadCount($id,$count = false)
    {
        if (isset($id)) {
            $identity = Zend_Auth::getInstance()->getStorage()->read();
            $ttele = new Zend_Db_Table('master.telegrams');

            //ini ceritanya mau bikin subquery
            //ini query pertama
            $qtele = $ttele->select()->setIntegrityCheck(false)
                ->from(array('master.telegrams'))
                ->where("kepada_id LIKE '%,{$id},%' OR tembusan_id LIKE '%,{$id},%'")
                ->where("jadwal <= '".date('m/d/Y H:i:s')."' OR jadwal is NULL")
                ->where("isdraft is FALSE");

            $cekIdTeam = $ttele->fetchAll($qtele);
            if(count($cekIdTeam))
            {
                foreach($cekIdTeam as $cekId)
                {
                    if($cekId['team_id'] != 0 && $identity->role_id != 9)
                    {
                        $qtele->where("team_id = ?", $identity->id_team );
                    }
                }
            }

            //ini query kedua
            $qtelog = $ttele->select()->setIntegrityCheck(false)
                ->from(array('user.login_telegram'))
                ->where('id_login = ?', $identity->id) ;

            //digabungin disini
            $query = $ttele->select()->setIntegrityCheck(false)
                ->from(array('tele' => $qtele), array('tele.idtelegram'))
                ->joinLeft(array('telog' => $qtelog),'telog."id_telegram" = tele."idtelegram"',array())
                ->where('telog."id_telegram" is NULL');


            if($count){
                return $ttele->fetchAll($query)->count();
            }
            else{
                $this->view->unreadCount = $ttele->fetchAll($query)->count();
            }
        }
        else {
            if($count){return 0;}
            else{
                $this->view->unreadCount = 0;
            }
        }
    }

    public function notification($id)
    {
        if (isset($id)) {
            $identity = Zend_Auth::getInstance()->getStorage()->read();
            $ttele = new Zend_Db_Table('master.telegrams');

            //ini ceritanya mau bikin subquery
            //ini query pertama
            $qtele = $ttele->select()->setIntegrityCheck(false)
                ->from(array('master.telegrams'))
                ->where("kepada_id LIKE '%,{$id},%' OR tembusan_id LIKE '%,{$id},%'")
                ->where("jadwal <= '".date('m/d/Y H:i:s')."' OR jadwal is NULL")
                ->where("isdraft is FALSE");

                $cekIdTeam = $ttele->fetchAll($qtele);
                if(count($cekIdTeam))
                {
                    foreach($cekIdTeam as $cekId)
                    {
                        if($cekId['team_id'] != 0 && $identity->role_id != 9)
                        {
                            $qtele->where("team_id = ?", $identity->id_team );
                        }
                    }
                }

            //ini query kedua
            $qtelog = $ttele->select()->setIntegrityCheck(false)
                ->from(array('user.login_telegram'))
                ->where('id_login = ?', $identity->id) ;

            //digabungin disini
            $query = $ttele->select()->setIntegrityCheck(false)
                ->from(array('tele' => $qtele), array('tele.idtelegram','tele.nomor_telegram','tele.datetime'))
                ->joinLeft(array('telog' => $qtelog),'telog."id_telegram" = tele."idtelegram"',array())
                ->where('telog."id_telegram" is NULL');

                return $ttele->fetchAll($query)->toArray();

        }
    }
}