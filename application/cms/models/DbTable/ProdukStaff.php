<?php
/**
 * Datatables untuk CRUD Produk Staff
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_Datatables_ProdukStaff extends App_Datatables
{
	public function getColumns() 
	{
		if (App_Auth::isAllowed('cms', 'produk.staff', 'add')) :
			return array(
				'nama_product_staff',
				'datetime',
				'langkah',
			);
		else:
			return array(
				'nama_product_staff',
				'datetime',
				'kode_skenario',
				'jenis_kogas',
				'nama_user',
				'langkah',
			);
		endif;

	}
	
	public function getTotalRecords() 
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();

        //mencari id_team-login dan id_jabatan-produkstaff
        $user = new Zend_Db_Table('user.logins');
        $queryuser = $user->select()->setIntegrityCheck(false)
            ->from(array('loguser' => 'user.logins'), array(
            'id',
            'username',
            'id_team',
            'id_jabatan' => 'juri.id_jabatan',
        ))
            ->joinLeft(array('juri' => 'user.penilai'), 'juri."id_login" = loguser."id"', array())
            ->where('loguser."id" = ?', $identity->id)
        ;
        $rowuser = $user->fetchRow($queryuser);


        //untuk datatable
        $table = new Zend_Db_Table('master.produk_staff');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('prod' => 'master.produk_staff'), array(
            'id_produk_staff',
            'nama_product_staff' => 'mprod.nama_product_staff',
            'datetime',
            'kode_skenario' => 'skenario.nomor',
            'jenis_kogas' => 'kogas.nama_jabatan',
            'nama_user' => 'pembuat.username',
            'langkah' => 'langkah.nama_langkah',
        ))
//			->join(array('pembuat' => 'user.logins'), 'pembuat."id" = prod."id_user"', array())
//			->join(array('tim' => 'user.Team'), 'tim."id" = pembuat."id_team"', array())
//			->join(array('skenario' => 'latihan.skenario'), 'skenario."id" = tim."kode_skenario"', array())
//			->join(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', array())
//			->join(array('langkah' => 'master.M_LANGKAH_PROSRENMIL'), 'langkah."id_langkah" = mprod."id_langkah"', array())
//			->join(array('kogas' => 'master.M_JABATAN'), 'kogas."id_jabatan" = mprod."id_jabatan"', array())

            ->joinLeft(array('pembuat' => 'user.logins'), 'pembuat."id" = prod."id_user"', array())
            ->joinLeft(array('tim' => 'user.Team'), 'tim."id" = pembuat."id_team"', array())
            ->joinLeft(array('skenario' => 'latihan.skenario'), 'skenario."id" = tim."kode_skenario"', array())
            ->joinLeft(array('pros' => 'master.M_PROSRENMIL'),'pros."id_prosrenmil" = skenario."prosrenmil_id"')
            ->joinLeft(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', array())
//            ->joinLeft(array('langkah' => 'master.langkah'), 'langkah."nama_prosrenmil" like pros."nama_prosrenmil" AND langkah."nomor_langkah" = mprod."id_langkah"', array())
            ->joinLeft(array('langkah' => 'master.M_LANGKAH_PROSRENMIL'), 'langkah."id_langkah" = mprod."id_langkah"', array())
            ->joinLeft(array('kogas' => 'master.M_JABATAN'), 'kogas."id_jabatan" = mprod."id_jabatan"', array())
        ;

        if($identity->role != 'Wasdal' || $identity->role_id != 9)
        {
            //ambil produk staff milik team yg sama dengan current login(user)
            $query->where('pembuat."id_team" = ?', $rowuser['id_team']);
            if ($rowuser['id_jabatan']){

                //ambil produk staff berdasarkan jabatan kalo ada id_jabatan (khusus user.login penilai)
                $query->where('kogas."id_jabatan" = ?', $rowuser['id_jabatan']);
            }
        }

//        $this->_search($query);
//echo $query;exit;
        return $table->fetchAll($query)->count();
	}
	
	public function getTotalDisplayRecords()
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();

        //mencari id_team-login dan id_jabatan-produkstaff
        $user = new Zend_Db_Table('user.logins');
        $queryuser = $user->select()->setIntegrityCheck(false)
            ->from(array('loguser' => 'user.logins'), array(
            'id',
            'username',
            'id_team',
            'id_jabatan' => 'juri.id_jabatan',
        ))
            ->joinLeft(array('juri' => 'user.penilai'), 'juri."id_login" = loguser."id"', array())
            ->where('loguser."id" = ?', $identity->id)
        ;
        $rowuser = $user->fetchRow($queryuser);


        //untuk datatable
        $table = new Zend_Db_Table('master.produk_staff');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('prod' => 'master.produk_staff'), array(
            'id_produk_staff',
            'nama_product_staff' => 'mprod.nama_product_staff',
            'datetime',
            'kode_skenario' => 'skenario.nomor',
            'jenis_kogas' => 'kogas.nama_jabatan',
            'nama_user' => 'pembuat.username',
            'langkah' => 'langkah.nama_langkah',
        ))
//			->join(array('pembuat' => 'user.logins'), 'pembuat."id" = prod."id_user"', array())
//			->join(array('tim' => 'user.Team'), 'tim."id" = pembuat."id_team"', array())
//			->join(array('skenario' => 'latihan.skenario'), 'skenario."id" = tim."kode_skenario"', array())
//			->join(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', array())
//			->join(array('langkah' => 'master.M_LANGKAH_PROSRENMIL'), 'langkah."id_langkah" = mprod."id_langkah"', array())
//			->join(array('kogas' => 'master.M_JABATAN'), 'kogas."id_jabatan" = mprod."id_jabatan"', array())

            ->joinLeft(array('pembuat' => 'user.logins'), 'pembuat."id" = prod."id_user"', array())
            ->joinLeft(array('tim' => 'user.Team'), 'tim."id" = pembuat."id_team"', array())
            ->joinLeft(array('skenario' => 'latihan.skenario'), 'skenario."id" = tim."kode_skenario"', array())
            ->joinLeft(array('pros' => 'master.M_PROSRENMIL'),'pros."id_prosrenmil" = skenario."prosrenmil_id"')
            ->joinLeft(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', array())
//            ->joinLeft(array('langkah' => 'master.langkah'), 'langkah."nama_prosrenmil" like pros."nama_prosrenmil" AND langkah."nomor_langkah" = mprod."id_langkah"', array())
            ->joinLeft(array('langkah' => 'master.M_LANGKAH_PROSRENMIL'), 'langkah."id_langkah" = mprod."id_langkah"', array())
            ->joinLeft(array('kogas' => 'master.M_JABATAN'), 'kogas."id_jabatan" = mprod."id_jabatan"', array())
        ;

        if($identity->role != 'Wasdal' || $identity->role_id != 9)
        {
            //ambil produk staff milik team yg sama dengan current login(user)
            $query->where('pembuat."id_team" = ?', $rowuser['id_team']);
            if ($rowuser['id_jabatan']){

                //ambil produk staff berdasarkan jabatan kalo ada id_jabatan (khusus user.login penilai)
                $query->where('kogas."id_jabatan" = ?', $rowuser['id_jabatan']);
            }
        }

        $this->_search($query);

        return $table->fetchAll($query)->count();
	}

	/**
	 * Di-override karena postgresql tidak bisa menggunakan column alias di
	 * where clause
	 */
	protected function _search($query)
	{
		$strwhere = '';
		if ($this->_search) {
			foreach ($this->_searchable as $col => $isSearchable) {
				if ($isSearchable) {
					switch ($col) {
						case 'nama_product_staff':
							$strwhere .='OR mprod."nama_product_staff" LIKE \'%'.$this->_search.'%\' ';
							break;
						case 'kode_skenario':
							$strwhere .='OR skenario."nomor" LIKE \'%'.$this->_search.'%\' ';
							break;
						case 'jenis_kogas':
							$strwhere .='OR kogas."nama_jabatan" LIKE \'%'.$this->_search.'%\' ';
							break;
						case 'nama_user':
							$strwhere .='OR pembuat."username" LIKE \'%'.$this->_search.'%\' ';
							break;
						case 'langkah':
							$strwhere .='OR langkah."nama_langkah" LIKE \'%'.$this->_search.'%\' ';
							break;
						default:
							$strwhere .='OR "'.$col.'" LIKE \'%'.$this->_search.'%\' ';
							break;
					}
				}
			}
			if ($strwhere != ''){
				$strwhere = trim($strwhere,'OR ');
			}
			$query->where($strwhere);
		}
	}

	public function retrieveData()
	{
		$identity = Zend_Auth::getInstance()->getStorage()->read();

		//mencari id_team-login dan id_jabatan-produkstaff
		$user = new Zend_Db_Table('user.logins');
		$queryuser = $user->select()->setIntegrityCheck(false)
			->from(array('loguser' => 'user.logins'), array(
				'id',
				'username',
				'id_team',
				'id_jabatan' => 'juri.id_jabatan',
			))
			->joinLeft(array('juri' => 'user.penilai'), 'juri."id_login" = loguser."id"', array())
			->where('loguser."id" = ?', $identity->id)
		;
		$rowuser = $user->fetchRow($queryuser);

        //print_r($rowuser);exit;

		//untuk datatable
		$table = new Zend_Db_Table('master.produk_staff');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('prod' => 'master.produk_staff'), array(
				'id_produk_staff',
				'nama_product_staff' => 'mprod.nama_product_staff',
				'datetime',
                'langkah' => 'langkah.nama_langkah',
				'kode_skenario' => 'skenario.nomor',
				'nama_user' => 'pembuat.username',
			))
//			->join(array('pembuat' => 'user.logins'), 'pembuat."id" = prod."id_user"', array())
//			->join(array('tim' => 'user.Team'), 'tim."id" = pembuat."id_team"', array())
//			->join(array('skenario' => 'latihan.skenario'), 'skenario."id" = tim."kode_skenario"', array())
//			->join(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', array())
//			->join(array('langkah' => 'master.M_LANGKAH_PROSRENMIL'), 'langkah."id_langkah" = mprod."id_langkah"', array())
//			->join(array('kogas' => 'master.M_JABATAN'), 'kogas."id_jabatan" = mprod."id_jabatan"', array())

            ->joinLeft(array('pembuat' => 'user.logins'), 'pembuat."id" = prod."id_user"', array())
            ->joinLeft(array('tim' => 'user.Team'), 'tim."id" = pembuat."id_team"', array())
            ->joinLeft(array('skenario' => 'latihan.skenario'), 'skenario."id" = tim."kode_skenario"', array())
            ->joinLeft(array('pros' => 'master.M_PROSRENMIL'),'pros."id_prosrenmil" = skenario."prosrenmil_id"', array())
            ->joinLeft(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', array())
//            ->joinLeft(array('langkah' => 'master.langkah'), 'langkah."nama_prosrenmil" like pros."nama_prosrenmil" AND langkah."nomor_langkah" = mprod."id_langkah"', array())
            ->joinLeft(array('langkah' => 'master.M_LANGKAH_PROSRENMIL'), 'langkah."id_langkah" = mprod."id_langkah"', array())
            ->joinLeft(array('kogas' => 'master.M_JABATAN'), 'kogas."id_jabatan" = mprod."id_jabatan"', array())
		;

        if($identity->role != 'Wasdal' || $identity->role_id != 9)
        {
            //ambil produk staff milik team yg sama dengan current login(user)
            $query->where('pembuat."id_team" = ?', $rowuser['id_team']);
            if ($rowuser['id_jabatan']){

                //ambil produk staff berdasarkan jabatan kalo ada id_jabatan (khusus user.login penilai)
                $query->where('kogas."id_jabatan" = ?', $rowuser['id_jabatan']);
            }
        }

		$query->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset);

		$this->_search($query);

		$result = array();
		$raw = $table->fetchAll($query);


		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);
			$tgl = date_create($t[1]);
			$t[1] = date_format($tgl,'H:i:s d/m/Y');

			$x = '
				<a href="'.$hUrl->url(array('action'=>'download', 'id'=>$id)).'">Download</a> |
				<a href="'.$hUrl->url(array('action'=>'read', 'id'=>$id)).'" target="_blank">Baca</a>
				';

			if (App_Auth::isAllowed('cms', 'produk.staff', 'del')) :
				$x .= ' | <a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			endif;

			$t[] = $x;
            $result[] = $t;
		}

//        print_r($result);
//        exit;

		return $result;
	}
}