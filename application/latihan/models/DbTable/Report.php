<?php
class Latihan_Model_DbTable_Report extends Zend_Db_Table_Abstract
{
    protected $_name = 'latihan.rekap';
    protected $_tableName = 'latihan.rekap';
    protected $_primary = 'id';

    public function getreport()
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from(array('rekap' =>$this->_tableName))
                    ->join(array('tim' => 'user.Team'), 'tim."id" = rekap."id_team"', array('tim.team_name'))
                    ->join(array('ps' => 'master.produk_staff'), 'ps."id_produk_staff" = rekap."id_product_staff"', null)
                    ->join(array('mps' => 'master.M_PRODUCT_STAFF'), 'mps."id" = ps."idm_product_staff"', array('mps.nama_product_staff'))
					->order('tim.team_name ASC')
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

    public function getidreport($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from(array('report' => $this->_tableName))
                    ->join(array('ps' => 'master.produk_staff'), 'ps."id_produk_staff" = report."id_product_staff"', null)
                    ->join(array('mps' => 'master.M_PRODUCT_STAFF'), 'mps."id" = ps."idm_product_staff"', array('id_product_staff' => 'mps.id','mps.nama_product_staff'))
                    ->join(array('tim' => 'user.Team'), 'tim."id" = report."id_team"', array('id_team' => 'tim.id','tim.team_name'))
                    ->join(array('sken' => 'latihan.skenario'), 'sken."id" = tim."kode_skenario"', array('sken.nomor'))
                    ->where('report."id" = '."'".$id."'")
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

	public function getreportcustom( $idps, $idteam)
	{
		$query = $this->select()->setIntegrityCheck(false)
			->from(array('report' => $this->_tableName))
			->join(array('ps' => 'master.M_PRODUCT_STAFF'), 'ps."id" = report."id_product_staff"', array('id_product_staff' => 'ps.id','ps.nama_product_staff'))
			->join(array('tim' => 'user.Team'), 'tim."id" = report."id_team"', array('id_team' => 'tim.id','tim.team_name'))
			->join(array('sken' => 'latihan.skenario'), 'sken."id" = tim."kode_skenario"', array('sken.nomor'))
			->where('report."id_product_staff" = ?', $idps)
			->where('report."id_team" = ?', $idteam)
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


    public function all()
    {
        $query = $this->select()
                    ->from($this->_tableName)
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

    public function updatereport($id, $nilai)
    {
        $data = array(
            'nilai' => $nilai
        );
        $this->update($data, "id = '".$id."'");
    }

    public function getPS()
    {
        $table = new Zend_Db_Table('master.M_PRODUCT_STAFF');
        $query = $table->select()
                        ->from('master.M_PRODUCT_STAFF')
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
}