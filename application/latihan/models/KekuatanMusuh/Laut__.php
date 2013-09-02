<?php
/**
 * Model untuk Kekuatan Musuh - Laut
 *
 * Mencakup table latihan.kekuatan_musuh_laut <- 1 to many -< latihan.kekuatan_musuh_laut_detail
 *
 * @author Kanwil
 */

class Latihan_Model_KekuatanMusuh_Laut
{
	protected $_skenario;
	protected $_primary = 'id';
	protected $_id = null;
	protected $_row = array();
	protected $_details = null;
	
	/**
	 * @param App_Model_Crud $skenario object skenario parent dari kekuatan ini
	 * @param int $id diperlukan untuk edit/del
	 */
	public function __construct($skenario, $id = null)
	{
		$this->_skenario = $skenario;
		if (isset($id)) {
			// coba cari 
			$table = $this->table();
			$rowset = $table->find($id);
			if (count($rowset) > 0) {
				$this->_id = $id;
				$this->_row = $rowset->current()->toArray();
				unset($this->_row['id']);
			} else {
				$this->_id = null;
			}
		} else {
			$this->_id = null;
		}
	}
	
	/**
	 * @return Zend_Db_Table tempat menyimpan kekuatan musuh bagian laut
	 */
	public function table()
	{
		return new Zend_Db_Table('latihan.kekuatan_musuh_laut');
	}
	
	/**
	 * @return Zend_Db_Table tempat menyimpan detail kekuatan musuh bagian laut
	 */
	public function tableDetail()
	{
		return new Zend_Db_Table('latihan.kekuatan_musuh_laut_detail');
	}
	
	/**
	 * Apakah object ini ada di database?
	 * @return bool
	 */
	public function exists()
	{
		return $this->_id !== null;
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
	
	/**
	 * Memvalidasi data sekaligus meng-assign nilai dari POST data
	 * @param array $postData data dari form HTML
	 * @return bool
	 */
	public function isValid($postData)
	{
		// validasi
		$vFloat = new Zend_Validate_Float();
		$this->_row['nama'] = $postData['nama'];
		$this->_row['negara'] = $postData['negara'];
		// longitude harus float
		if (!$vFloat->isValid($postData['longitude'])) return false;
		$this->_row['longitude'] = $postData['longitude'];
		// latitude harus float
		if (!$vFloat->isValid($postData['latitude'])) return false;
		$this->_row['latitude'] = $postData['latitude'];
		
		// assign details
		$this->_details = $postData['detail'];
		return true;
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama kolom
	 * @return array
	 */
	public function toArray($withId = false)
	{
		$row = $this->_row;
		if ($withId) {
			$row[$this->_primary] = $this->_id;
		}
		return $row;
	}
	
	/**
	 * Mengembalikan seluruh detail kekuatan ini
	 * @return Zend_Db_Table_Rowset
	 */
	public function details()
	{
		if (!isset($this->_details)) {
			if ($this->exists()) {
				$table = $this->tableDetail();
				$this->_details = $table->fetchAll($table->getAdapter()->quoteInto('parent_id = ?', $this->_id));
			} else {
				$this->_details = array();
			}
		}
		return $this->_details;
	}
	
	/**
	 * Mengembalikan kondisi where untuk object ini
	 */
	public function where()
	{
		$db = $this->table()->getAdapter();
		return $db->quoteInto($db->quoteIdentifier($this->_primary) . " = ?", $this->_id);
	}
	
	/**
	 * Simpan penambahan/perubahan ke database
	 */
	public function save()
	{
		$table = $this->table();
		if ($this->exists()) {
			$table->update($this->toArray(), $this->where());
		} else {
			$this->_row['skenario_id'] = $this->_skenario->getId();
			$table->insert($this->toArray());
			$this->_id = $table->getAdapter()->lastSequenceId('latihan.kekuatan_musuh_laut_id_seq'); // hardcoded!
		}
		// simpan detail
		$table = $this->tableDetail();
		$db = $table->getAdapter();
		
		// hapus yg tidak tersimpan
		$savedIds = array();
		foreach ($this->_details as $row) {
			if ($row['id']) {
				$savedIds[] = $row['id'];
			}
		}
		$delQuery = array(
			$db->quoteInto('parent_id = ?', $this->_id),
		);
		if (count($savedIds)) {
			$delQuery[] = $db->quoteInto('id NOT IN (?)', $savedIds);
		}
		$table->delete($delQuery);
		
		// insert dan update
		foreach ($this->_details as $row) {
			if ($row['id']) {
				$where = $table->getAdapter()->quoteInto('id = ?', $row['id']);
				unset($row['id']);
				$table->update($row, $where);
			} else {
				unset($row['id']);
				$row['parent_id'] = $this->_id;
				$table->insert($row);
			}
		}
	}
	
	/**
	 * Hapus object ini
	 */
	public function delete()
	{
		if (!$this->exists()) return;
		$table = $this->table();
		$table->delete($this->where());
		$this->_id = null;
		// detail otomatis terhapus oleh constraint database
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
	 * Mengembalikan semua kekuatan laut lengkap dengan detail untuk skenario ini
	 * @return array
	 */
	public function allWithKekuatan()
	{
		$table = $this->table();
		$query = $table->select()
			->where('skenario_id = ?', $this->_skenario->getId())
		;
		$raw = $table->fetchAll($query);
		
		$detailTable = $this->tableDetail();
		$db = $detailTable->getAdapter();
		$result = array();
		foreach ($raw as $row) {
			$temp = $row->toArray();
			// fetch kekuatan
			$temp['kekuatan'] = array();
			$detailRaw = $detailTable->fetchAll($db->quoteInto('parent_id = ?', $row->id));
			foreach ($detailRaw as $dr) {
				$temp['kekuatan'][] = $dr->jumlah .'|'. $dr->jenis;
			}
			$result[] = $temp;
		}
		return $result;
	}
}