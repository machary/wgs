<?php
/**
 * Datatables untuk CRUD rol sheet
 * @author Febi
 */

class Latihan_Model_Datatables_Penilaian extends App_Datatables
{
	public function getColumns()
	{
		return array(
			'nama_team',
			'updated',
			'',
		);
	}

	public function getTotalRecords()
	{
		if($this->_params['idps'] == 'null') return 0;
		$table = new Zend_Db_Table('master.produk_staff');
		$query = $this->queryBuild($table);
		return $table->fetchAll($query)->count();
	}

	public function getTotalDisplayRecords()
	{
		if($this->_params['idps'] == 'null') return 0;
		$table = new Zend_Db_Table('master.produk_staff');
		$query = $this->queryBuild($table);
		$this->_search($query);

		return $table->fetchAll($query)->count();
	}

	public function retrieveData()
	{
		if($this->_params['idps'] == 'null') return false;

		$table = new Zend_Db_Table('master.produk_staff');
		$query = $this->queryBuild($table);

		$this->_search($query);

        $raw = $table->fetchAll($query);

		$result = array();
		$hUrl = new Zend_View_Helper_Url();

		$identity = Zend_Auth::getInstance()->getStorage()->read();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);
			$t[] = '<a href="'.$hUrl->url(array('action'=>'nilai', 'id'=>$id)).'">Nilai</a>';

			$result[] = $t;
		}

		return $result;
	}

	public function queryBuild( $table)
	{
		if($this->_params['idps'] == 'null') return false;


        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('prod' => 'master.produk_staff'), array( 'prod.id_produk_staff','tim.team_name', 'kogas.nama_jabatan', 'skenario.nomor', 'rekap.nilai'))
//			->join(array('pembuat' => 'user.logins'), 'pembuat."id" = prod."id_user"', array())
//			->join(array('tim' => 'user.Team'), 'tim."id" = pembuat."id_team"', array())
//			->join(array('skenario' => 'latihan.skenario'), 'skenario."id" = tim."kode_skenario"', array())
//			->join(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', array())
//			->join(array('langkah' => 'master.M_LANGKAH_PROSRENMIL'), 'langkah."id_langkah" = mprod."id_langkah"', array())
//			->join(array('kogas' => 'master.M_JABATAN'), 'kogas."id_jabatan" = mprod."id_jabatan"', array())

            ->joinLeft(array('pembuat' => 'user.logins'), 'pembuat."id" = prod."id_user"', array())
            ->joinLeft(array('tim' => 'user.Team'), 'tim."id" = pembuat."id_team"', array())
            ->joinLeft(array('skenario' => 'latihan.skenario'), 'skenario."id" = tim."kode_skenario"', array())
            ->joinLeft(array('pros' => 'master.M_PROSRENMIL'),'pros."id_prosrenmil" = skenario."prosrenmil_id"', null)
            ->joinLeft(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', array())
//            ->joinLeft(array('langkah' => 'master.langkah'), 'langkah."nama_prosrenmil" like pros."nama_prosrenmil" AND langkah."nomor_langkah" = mprod."id_langkah"', null)
            ->joinLeft(array('langkah' => 'master.M_LANGKAH_PROSRENMIL'), 'langkah."id_langkah" = mprod."id_langkah"', array())

            ->joinLeft(array('role' => 'user.roles'), 'pembuat."role_id" = role."id"', null)
            ->joinLeft(array('kogas' => 'master.M_JABATAN'), 'kogas."id_jabatan" = role."kogas"', null)

            ->joinLeft(array('penilai' => 'user.penilai'), 'tim."id" = penilai."id_team" AND penilai."id_jabatan" = kogas."id_jabatan"', null)
            ->joinleft(array('rekap' => 'latihan.rekap'), 'rekap."id_product_staff" = prod."idm_product_staff" AND rekap."id_team" = tim."id"', null)
            ->where('penilai."id_login" = ?', $this->_params['log_id'])
            ->where('prod."idm_product_staff" = ?', $this->_params['idps'])
            ->order('tim.team_name ASC');

//
//
//		$query = $table->select()->setIntegrityCheck(false)
//			->from(array('prod' => 'master.produk_staff'), array( 'prod.id_produk_staff','tim.team_name', 'jab.nama_jabatan', 'sken.nomor', 'rekap.nilai'))
//			->join(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', null)
//			->join(array('jab' => 'master.M_JABATAN'), 'jab."id_jabatan" = mprod."id_jabatan"', null)
//            ->join(array('log' => 'user.logins'), 'log."id" = prod."id_user"', null)
//            ->join(array('tim' => 'user.Team'), 'tim."id" = log."id_team"', null)
//            ->join(array('penilai' => 'user.penilai'), 'penilai."id_team" = tim."id"', null)
//            ->join(array('sken' => 'latihan.skenario'), 'sken."id" = tim."kode_skenario"', null)
//			->joinleft(array('rekap' => 'latihan.rekap'), 'rekap."id_product_staff" = prod."idm_product_staff" AND rekap."id_team" = tim."id"', null)
//
//            ->where('penilai."id_login" = ?', $this->_params['log_id'])
//			->where('prod."id_produk_staff" = ?', $this->_params['idps'])
//			->order('tim.team_name ASC');
//

		return $query;
	}
}
