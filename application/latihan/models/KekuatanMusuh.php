<?php
/**
 * Model untuk Kekuatan Musuh - Darat, Laut, Udara
 *
 * @author irfan.muslim@sangkuriang.co.id
 */

class Latihan_Model_KekuatanMusuh
{
	protected $_skenario;
	protected $_primary = 'id';
	protected $_id = null;
	protected $_row = array();
	protected $_details = null;
	protected $_matra = 0;

	// jenis symbol taktis hardcode
	protected $_array_jenis = array(
		'0'=>array('infantri',), //just for testing
		'1'=>array(
			'm infantri',
			'm infantri mekanis',
			'm linud',
			'm kavaleri roda',
			'm kavaleri tank',
			'm artileri medan',
			'm artileri hanud',
			'm zeni',
			'm teritorial'
		),
		'2'=>array(
			'm komando operasional',
			'm kuat log',
			'm marinir',
			'm landasan udara',
			'm kapal',
			'm sayap putar',
			'm sayap tetap'
		),
		'3'=>array(
			'm sayap putar',
			'm sayap tetap',
			'm landasan udara',
			'm pangkalan udara',
			'm radar'
		)
	);
	
	/**
	 * @param App_Model_Crud $skenario object skenario parent dari kekuatan ini
	 * @param int $id diperlukan untuk edit/del
	 */
	public function __construct($skenario, $id = null, $matra = 0)
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

		if(isset($matra)){
			$this->_matra = $matra;
		}
	}
	
	/**
	 * @return Zend_Db_Table tempat menyimpan kekuatan musuh
	 */
	public function table()
	{
		return new Zend_Db_Table('latihan.kekuatan_musuh');
	}

	/**
	 * @return Zend_Db_Table tempat menyimpan detail kekuatan musuh
	 */
	public function tableDetail()
	{
		return new Zend_Db_Table('latihan.kekuatan_musuh_detail');
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

		if ($this->_matra) {
			$query->where('matra = ?', $this->_matra);
		}

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
		$this->_row['matra'] = $this->_matra;
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
				$query = $table->select()->setIntegrityCheck(false)
					->from(array('detail' => 'latihan.kekuatan_musuh_detail'))
					->joinLeft(array('symbol' => 'master.simbol_taktis'), 'symbol."id" = detail."id_symbol"',array(
					'nama','singkatan','jenis','filepath'
				))
					->where('detail."parent_id" = ?',$this->_id);
				;

				$this->_details = $table->fetchAll($query);
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
			$this->_id = $table->getAdapter()->lastSequenceId('latihan.kekuatan_musuh_id_seq'); // hardcoded!
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
	public function jenisOptionSimbol($matra)
	{
		$arrJ = $this->_array_jenis[$matra];
		// ambil dari table master.simbol_taktis
		$table = new Zend_Db_Table('master.simbol_taktis');
		$query = $table->select()
			->from('master.simbol_taktis')
			->where('jenis IN (?)',$arrJ)
		;
		return $table->getAdapter()->fetchAll($query);
	}
	

	/**
	 * Mengembalikan semua kekuatan laut lengkap dengan detail untuk skenario ini
	 * @return array
	 */
	public function allWithKekuatan()
	{
		$imgPrefix = Zend_Controller_Front::getInstance()->getBaseUrl();
		$table = $this->table();
		$query = $table->select()
			->where('skenario_id = ?', $this->_skenario->getId())
		;

		if ($this->_matra) {
			$query->where('matra = ?', $this->_matra);
		}

		$raw = $table->fetchAll($query);

		$detailTable = $this->tableDetail();
		$result = array();
		foreach ($raw as $row) {
			$temp = $row->toArray();
			// fetch kekuatan
			$temp['kekuatan'] = array();
			$queryd = $detailTable->select()->setIntegrityCheck(false)
				->from(array('detail' => 'latihan.kekuatan_musuh_detail'))
				->joinLeft(array('symbol' => 'master.simbol_taktis'), 'symbol.id = detail.id_symbol')
				->where('detail."parent_id" = ?', $row->id)
			;
			$detailRaw = $detailTable->fetchAll($queryd);

			foreach ($detailRaw as $dr) {
				$temp['kekuatan'][] = '<img src="'.$imgPrefix.$dr->filepath.'" height="24" width="24"/> | '.$dr->jumlah .'| '.$dr->keterangan;
			}
			$result[] = $temp;
		}
		return $result;
	}

	function simpank_detail($data)
	{
		$table = $this->tableDetail();
		$table->insert($data);
		$id = $table->getAdapter()->lastSequenceId('latihan.kekuatan_musuh_detail_id_seq');

		return $id;
	}

	function deletek_detail($id)
	{
		$table = $this->tableDetail();
		$db = $this->table()->getAdapter();
		$table->delete($db->quoteInto($db->quoteIdentifier('id') . " = ?", $id));
	}

}