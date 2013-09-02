<?php
/**
 * Model untuk Kekuatan Musuh- Udara
 *
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Latihan_Model_KekuatanMusuh_Udara
{
	protected $_skenario;
    protected $_data;
    protected $_dataDetail;
    protected $_id;
    protected $_primary = 'id';

	/**
	 * @param App_Model_Crud $skenario object skenario parent dari kekuatan ini
	 * @param string $kotama diperlukan untuk CRUD
	 */
	public function __construct($skenario, $id)
	{
		$this->_skenario = $skenario;

        if (isset($id)) {
            $table = $this->table();
            $rowset = $table->find($id);
            if (count($rowset) > 0) {
                $this->_id = $id;
                $this->_data = $rowset->current()->toArray();
            } else {
                $this->_id = null;
            }
        } else {
            $this->_id = null;
        }
	}
	
	public function table()
	{
		return new Zend_Db_Table('latihan.kekuatan_musuh_udara');
	}
	
    public function tableDetail()
   	{
   		return new Zend_Db_Table('latihan.kekuatan_musuh_udara_detail');
   	}
	/**
	 * Mengembalikan seluruh data 
	 * @return Zend_Db_Table_Rowset
	 */
	public function all()
	{
		$table = $this->table();
		$query = $table->select()
			->where('skenario_id = ?', $this->_skenario->getId())
		;
		return $table->fetchAll($query);
	}

    public function getData()
    {
        return $this->_data;
    }

    public function getDataDetail()
    {
        $tableDetail = $this->tableDetail();
        $query = $tableDetail->select()->setIntegrityCheck(false)
                ->from(array('ksd' => 'latihan.kekuatan_musuh_udara_detail'))
                ->join(array('sb' => 'master.simbol_taktis'), 'ksd."taktis_id" = sb."id"', array('sb.jenis', 'sb.nama as nama_simbol'))
                ->where('parent_id = ?', $this->_id)
        ;
        $result = $tableDetail->fetchAll($query);
        return $result->toArray();
    }

    /**
   	 * @param array $post POST data
   	 */
   	public function setFromPost($post)
   	{
   		// @TODO validasi
   		$this->_data = $post;
   	}

    /**
   	 * Apakah object ini ada di database?
   	 * @return bool
   	 */
   	public function exists()
   	{
   		return $this->_id !== null;
   	}


   	public function where()
   	{
   		$db = $this->table()->getAdapter();
   		return $db->quoteInto($db->quoteIdentifier($this->_primary) . " = ?", $this->_id);
   	}

    /**
   	 * Menyimpan ke database
   	 */
   	public function save()
   	{
       $table = $this->table();
       $tableDetail = $this->tableDetail();
       $db = $table->getAdapter();

        $data = array(
            'skenario_id' => $this->_skenario->getId(),
            'longitude' => $this->_data['txtLongitude'],
            'latitude' => $this->_data['txtLatitude'],
            'nama' => $this->_data['txtLokasi'],
        );

        if ($this->exists()) {
            $table->update($data, $this->where());
            $parentID = $this->_id; //dont move or del !important
            //delete detail lama
            self::deleteDetail();
            //save detail baru
            unset( $data );
            foreach($this->_data['detail'] as $key => $val ){
                $data = array(
                    'parent_id' => $parentID,
                    'taktis_id' => $val['taktis_id'],
                    'jumlah' => $val['jumlah'],
                    'keterangan' => $val['keterangan'],
                );
                $tableDetail->insert( $data );
            }
        } else {
            $this->_id = $table->insert( $data ); //return last inserted ID if success

            //save detail
            unset( $data );
            foreach($this->_data['detail'] as $key => $val ){
                $data = array(
                    'parent_id' => $this->_id,
                    'taktis_id' => $val['taktis_id'],
                    'jumlah' => $val['jumlah'],
                    'keterangan' => $val['keterangan'],
                );
                $tableDetail->insert( $data );
            }
        }
   	}

    /**
   	 * Hapus object ini dari database
   	 */
   	public function delete()
   	{
   		if (!$this->exists()) return false;
   		$table = $this->table();
   		$table->delete($this->where());
   	}

    /**
   	 * Hapus table data didetail
   	 */
   	public function deleteDetail()
   	{
   		if (!$this->exists()) return;
   		$tableDetail = $this->tableDetail();
        $where = $tableDetail->getAdapter()->quoteInto('parent_id = ?', $this->_id);
        $tableDetail->delete( $where );
   		$this->_id = null;
   	}
}