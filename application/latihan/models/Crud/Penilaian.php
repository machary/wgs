<?php
class Latihan_Model_Crud_Penilaian extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.produk_staff';
    protected $_tableName = 'master.produk_staff';
    protected $_primary = 'id_produk_staff';

    public function getalldata($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from(array('prod' => $this->_tableName), array(new Zend_Db_Expr('DISTINCT prod."idm_product_staff"')))
                    ->join(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', array('mprod.nama_product_staff'))
                    ->join(array('jab' => 'master.M_JABATAN'), 'jab."id_jabatan" = mprod."id_jabatan"', array('jab.nama_jabatan'))
                    ->join(array('penilai' => 'user.penilai'), 'penilai."id_jabatan" = jab."id_jabatan"', array())
                    ->join(array('tim' => 'user.Team'), 'tim."id" = penilai."id_team"', array('id_team' => 'tim.id', 'tim.team_name'))
                    ->join(array('log' => 'user.logins'), 'log."id" = penilai."id_login"', array('log.nama'))
                    ->join(array('sken' => 'latihan.skenario'), 'sken."id" = tim."kode_skenario"', array('sken.nomor'))
                    ->where('log."id" = '."'".$id."'")
                    ->order('tim.id ASC')
                    ->order('mprod.nama_product_staff ASC')
        ;

        $result = $this->fetchAll($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

	public function getallps($id)
	{
        $table = new Zend_Db_Table('master.produk_staff');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('prod' => 'master.produk_staff'), array(
            new Zend_Db_Expr('DISTINCT mprod.id'),
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
            ->joinLeft(array('penilai' => 'user.penilai'), 'tim."id" = penilai."id_team"', array())


			->where('penilai."id_login" = ?', $id)
			->order('mprod.nama_product_staff ASC')
		;

//        $query = $this->select()->setIntegrityCheck(false)
//                    ->from(array('ps' => $this->_tableName))
//                    ->join(array('mps' => 'master.M_PRODUCT_STAFF'), 'mps."id" = ps."idm_product_staff"', array('nama_product_staff'))
//                    ->join(array('log' => 'user.logins'), 'log."id" = ps."id_user"', array())
//                    ->join(array('penilai' => 'user.penilai'), 'penilai."id_team" = log."id_team"', array())
//                    ->where('penilai."id_login" = ?', $id)
//        ;

		$result = $this->fetchAll($query);

		if(!empty($result))
		{
			return $result->toArray();
		}
		else
		{
			return null;
		}
	}

//    public function getprodstaf($id)
//    {
//        $query = $this->select()->setIntegrityCheck(false)
//
//                    ->from(array('prod' => $this->_tableName), array('prod.id_produk_staff', 'prod.datetime', 'prod.filepath'))
//                    ->joinLeft(array('pembuat' => 'user.logins'), 'pembuat."id" = prod."id_user"', array('pembuat.nrp', 'pembuat.nama'))
//                    ->joinLeft(array('tim' => 'user.Team'), 'tim."id" = pembuat."id_team"', array('team_name', 'id_team' => 'id'))
//                    ->joinLeft(array('skenario' => 'latihan.skenario'), 'skenario."id" = tim."kode_skenario"', array())
//                    ->joinLeft(array('pros' => 'master.M_PROSRENMIL'),'pros."id_prosrenmil" = skenario."prosrenmil_id"', null)
//                    ->joinLeft(array('mprod' => 'master.M_PRODUCT_STAFF'), 'mprod."id" = prod."idm_product_staff"', array('nama_product_staff', 'idm_product_staff' => 'id'))
////                    ->joinLeft(array('langkah' => 'master.langkah'), 'langkah."nama_prosrenmil" like pros."nama_prosrenmil" AND langkah."nomor_langkah" = mprod."id_langkah"', null)
//                    ->join(array('langkah' => 'master.M_LANGKAH_PROSRENMIL'), 'langkah."id_langkah" = mprod."id_langkah"', array())
//                    ->joinLeft(array('kogas' => 'master.M_JABATAN'), 'kogas."id_jabatan" = mprod."id_jabatan"',  array('kogas.nama_jabatan'))
//                    ->joinLeft(array('penilai' => 'user.penilai'), 'tim."id" = penilai."id_team"', null)
//                    ->joinleft(array('rekap' => 'latihan.rekap'), 'rekap."id_product_staff" = prod."idm_product_staff" AND rekap."id_team" = tim."id"', null)
//
//
//
//
//                    ->where("id_produk_staff = '".$id."'")
//        ;
//
//        $result = $this->fetchRow($query);
//
//        if(!empty($result))
//        {
//            return $result->toArray();
//        }
//        else
//        {
//            return null;
//        }
//    }

    public function getprodstaf($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from( array( 'prod' => $this->_tableName ) )
                    ->joinLeft( array( 'mprod' => 'master.M_PRODUCT_STAFF' ), 'mprod."id" = prod."idm_product_staff"', array('mprod.nama_product_staff') )
                    ->joinLeft( array( 'login' => 'user.logins' ), 'login."id" = prod."id_user"', array( 'login.id_team' ) )
                    ->joinLeft( array( 'tim' => 'user.Team' ), 'tim."id" = login."id_team"', array('tim.team_name') )
                    ->where( 'prod."id_produk_staff" = ' . "'" . $id . "'" )
        ;

        $result = $this->fetchRow($query);
        return ( !empty( $result ) ) ? $result->toArray() : null;
    }

    public function getUrl($id)
    {
        $query = $this->select()
                    ->from($this->_tableName)
                    ->where("id_produk_staff = '".$id."'")
        ;

        $result = $this->fetchRow($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function getteam($id)
    {
        $table = new Zend_Db_Table('user.penilai');
        $query = $table->select()->setIntegrityCheck(false)
                    ->from(array('penilai' => 'user.penilai'), array())
                    ->join(array('tim' => 'user.Team'), 'tim."id" = penilai."id_team"', array('tim.id', 'tim.team_name'))
                    ->where('penilai."id_login" = '."'".$id."'")
                    ->group(array('penilai.id_login', 'penilai.id_team', 'tim.id', 'tim.team_name'))
                    ->order('tim.id ASC')
        ;

        $result = $table->fetchAll($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function addreport($ps, $team, $nilai, $keterangan)
    {
        $table = new Zend_Db_Table('latihan.rekap');

		$data = array(
			'id_product_staff'  => $ps,
			'id_team'  => $team,
			'nilai'  => $nilai,
            'keterangan'  => $keterangan
		);

        $exist = $table->fetchRow(array( "id_product_staff = {$ps}", "id_team = {$team}"));

        if(!count($exist))
        {
            $table->insert($data);
        }
        else
        {
            $table->update($data, 'id = ' . $exist['id']);
        }

    }

    public function getreport($id)
    {
        $table = new Zend_Db_Table('latihan.rekap');
        $query = $table->select()
                    ->from('latihan.rekap')
                    ->where('')
        ;
    }

    public function getlogteam($id)
    {
        $table = new Zend_Db_Table('user.penilai');
        $query = $table->select()->setIntegrityCheck(false)
                        ->from(array('penilai' => 'user.penilai'), array())
                        ->join(array('tim' => 'user.Team'), 'tim."id" = penilai."id_team"', array('tim.id', 'tim.team_name'))
                        ->join(array('sken' => 'latihan.skenario'), 'sken."id" = tim."kode_skenario"', array('sken.nomor'))
                        ->join(array('log' => 'user.logins'), 'log."id_team" = tim."id"', array('log_id' => 'log.id', 'log.nrp', 'log.nama'))
                        ->where('penilai."id_login" = '."'".$id."'")
        ;

        $result = $this->fetchAll($query);
        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function addindividu($log, $team, $nilai)
    {
        $table = new Zend_Db_Table('latihan.rekap_individu');
        $data = array(
            'id_team'  => $team,
            'id_login' => $log,
            'nilai'  => $nilai
        );
        $table->insert($data);
    }
}