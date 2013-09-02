<?php
/**
 * Model untuk Kekuatan Sendiri - Laut
 *
 * @author Kanwil
 */
 
class Latihan_Model_KekuatanSendiri_Laut
{
	protected $_kotama;
	protected $_id;
	protected $_skenario;
	protected $_data;
	protected $_primary = 'id';
	
	/**
	 * @param App_Model_Crud $skenario object skenario parent dari kekuatan ini
	 * @param string $kotama diperlukan untuk CRUD
	 */
	public function __construct($skenario, $id)
	{
		$this->_skenario = $skenario;
		//$this->_id = $id;
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
		return new Zend_Db_Table('latihan.kekuatan_sendiri_laut');
	}

    public function tableDetail()
   	{
   		return new Zend_Db_Table('latihan.kekuatan_sendiri_laut_detail');
   	}

	/**
	 * Mengembalikan seluruh data 
	 * @return Zend_Db_Table_Rowset
	 */
	public function all()
	{
		$table = $this->table();
		$query = $table->select()->setIntegrityCheck(false)
            ->from(array('a'=>'latihan.kekuatan_sendiri_laut'))
			->where('a."skenario_id" = ?', $this->_skenario->getId())
			//->where('a."kotama" = ?', $this->_kotama)
		;

		return $table->fetchAll($query);
	}

    public function getData()
    {
        return $this->_data;
    }

	public function getId($kotama)
	{
		$table = $this->table();
		$query = $table->select()->setIntegrityCheck(false)
            ->from(array('a'=>'latihan.kekuatan_sendiri_laut'))
			->where('a."skenario_id" = ?', $this->_skenario->getId())
			//->where('a."kotama" = ?', $kotama)
		;

		return $table->fetchRow($query)->id;
	}

	/**
	 * Mengembalikan seluruh data
	 * @return Zend_Db_Table_Rowset
	 */
	public function getByKotama()
	{
		$table = $this->table();
		$query = $table->select()->setIntegrityCheck(false)
            ->from(array('a'=>'latihan.kekuatan_sendiri_laut'))
			->where('a."skenario_id" = ?', $this->_skenario->getId())
			//->where('a."kotama" = ?', $this->_kotama)
		;

		return $table->fetchRow($query);
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
	 * Hapus object ini dari database
	 */
	public function delete()
	{
		if (!$this->exists()) return;
		$table = $this->table();
		$table->delete($this->where());
		//$this->_id = null;
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

	/**
	 * Menyimpan ke database
	 */
	public function save()
	{
		$table = $this->table();

		$table = $this->table();
		if ($this->exists()) {
			$table->update($this->_data, $this->where());
		} else {
			$this->_id = $table->insert($this->_data);
		}
		return $this->_id;
	}

	public function saveDetail($parent, $jenis, $jumlah)
	{
		$tabel = new Zend_Db_Table('latihan.kekuatan_sendiri_laut_detail');
		$tabel->_cols = array('id' => 'id', 'parent_id'=>'parent_id', 'jumlah'=>'jumlah','taktis_id'=>'taktis_id');
		$tabel->_primary = 'id';

		$data = array(
			'parent_id' => $parent,
			'jumlah'    => $jumlah,
			'taktis_id' => $jenis
		);
		$tabel->insert($data);
	}
	
	/**
	 * Mengembalikan pilihan jenis unsur untuk Unsur = Kapal
	 * @return array
	 */
	public function jenisOptionsForKapal()
	{
		// ambil dari table master.M_SHIP_SYMBOL
		$table = new Zend_Db_Table('master.M_SHIP_SYMBOL');
		$query = $table->select()
			->distinct()
			->from('master.M_SHIP_SYMBOL', array('SHIP_SYM_ABRR'))
		;
		return $table->getAdapter()->fetchCol($query);
	}
	
	/**
	 * Mengembalikan pilihan jenis unsur untuk Unsur = Pesud
	 * @return array
	 */
	public function jenisOptionsForPesud()
	{
		return array();
	}
	
	/**
	 * Mengembalikan pilihan jenis unsur untuk Unsur = Ranpur
	 * @return array
	 */
	public function jenisOptionsForRanpur()
	{
		return array();
	}
	
	/**
	 * Mengembalikan pilihan jenis unsur untuk Unsur = Pasukan Marinir
	 * @return array
	 */
	public function jenisOptionsForPasmar()
	{
		return array();
	}
	
	/**
	 * Mengembalikan semua kekuatan laut untuk skenario ini
	 * @return array
	 */
	public function allKekuatan()
	{
		$table = $this->table();
		$query = $table->select()->setIntegrityCheck(false)
            ->from(array('a'=>'latihan.kekuatan_sendiri_laut'))
            ->joinLeft(array('b'=>'latihan.kekuatan_sendiri_laut_detail'),'a."id"=b."parent_id"', array('jumlah'))
			->joinLeft(array('c'=>'master.simbol_taktis'),'c."id"=b."taktis_id"', array('jenis'))
            ->where('skenario_id = ?', $this->_skenario->getId())
		;
		$raw = $table->fetchAll($query);
		
		$result = array();
		foreach ($raw as $row) {
			$result[$row->kotama][] = $row->jumlah . '|' . $row->jenis;
		}
		return $result;
	}
}