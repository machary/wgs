<?php
/**
 * Model pendukung Rencana Operasi
 *
 * @author Kanwil
 */
 
class Ops_Model_Ro
{
	protected $_teamId = null; // untuk mencari cb terpilih
	protected $_chosen = null; // menyimpan info cb terpilih
	
	/**
	 * @param string|int $teamId 
	 */
	public function __construct($teamId)
	{
		$this->_teamId = $teamId;
		$table = $this->pilihanTable();
		$rowset = $table->find($teamId);
		if (count($rowset) > 0) {
			$this->_chosen = $rowset->current();
		} else {
			$this->_chosen = $table->createRow();
		}
	}
	
	public function pilihanTable()
	{
		return new Zend_Db_Table('latihan.pilihan_cb_kogab');
	}
	
	public function roTable()
	{
		return new Zend_Db_Table('ops.rencana_operasi');
	}

	public function roGabunganTable()
	{
		return new Zend_Db_Table('ops.rencana_operasi_gabungan');
	}
	
	/**
	 * Return data needed for a kogas
	 * @param string $column nama kolom yang ditunjuk
	 * @param string $cbClass nama class model CB
	 * @param array $routesClass daftar rute dan kelas yang menanganinya
	 * @return array
	 */
	protected function _kogasData($column, $cbClass, $routesClass)
	{
		if (!$this->_chosen->$column) { // jika belum ada yg dipilih
			return array();
		}
		$cb = new $cbClass(null, $this->_chosen->$column); // sesuaikan dengan kogas
		if (!$cb->exists()) { // jika cb id invalid
			return array();
		}
		// rute
		$result = array(
			'cb' => $cb,
		);

        foreach ($routesClass as $rute => $class) {
			$result[$rute] = $class::allObjects($cb->getId());
		}
		return $result;
	}

	public function _musuhData($skenId, $routesClass)
	{

	    $result = array();
		foreach ($routesClass as $rute => $class) {
			$result[$rute] = $class::allObjects($skenId);
		}
		return $result;
	}
	
	// musuh
	public function musuhData($skenId)
	{
        return $this->_musuhData($skenId, array(
			'laut' => 'Ops_Model_Musuh_Laut',
			'udara' => 'Ops_Model_Musuh_Udara',
		));
	}

	public function kogasgablaData()
	{
		return $this->_kogasData('kogasgabla', 'Ops_Model_Gabla_Cb', array(
			'laut' => 'Ops_Model_Gabla_Laut',
			'udara' => 'Ops_Model_Gabla_Udara',
		));
	}
	
	// Kogasgabfib
	public function kogasgabfibData()
	{
		return $this->_kogasData('kogasgabfib', 'Ops_Model_Gabfib_Cb', array(
			'laut' => 'Ops_Model_Gabfib_Laut',
			'udara' => 'Ops_Model_Gabfib_Udara',
		));
	}
	
	// Kogasgablinud
	public function kogasgablinudData()
	{
		return $this->_kogasData('kogasgablinud', 'Ops_Model_Gablinud_Cb', array(
			'linud' => 'Ops_Model_Gablinud_Linud',
			'udara' => 'Ops_Model_Gablinud_Udara',
		));
	}

    // Pasrat
   	public function pasratData()
   	{
   		return $this->_kogasData('pasrat', 'Ops_Model_Pasrat_Cb', array(
   			'darat' => 'Ops_Model_Pasrat_Darat',
   			'marinir' => 'Ops_Model_Pasrat_Marinir',
   		));
   	}
	
	// Kogasgabrat
	public function kogasgabratData()
	{
		return $this->_kogasData('kogasgabrat', 'Ops_Model_Gabrat_Cb', array(
			'darat' => 'Ops_Model_Gabrat_Darat',
		));
	}
	
	// Kogasud
	public function kogasudData()
	{
		return $this->_kogasData('kogasud', 'Ops_Model_Ud_Cb', array(
			'udara' => 'Ops_Model_Ud_Udara',
		));
	}
	
	/**
	 * Simpan pengaturan RO 
	 * @param string $kogas kogasgabla/kogasgabfib/etc
	 * @param array $json data yang dikirim oleh browser
		array of stdClass {
			id : primary key rute
			class_name : nama class yang menangani rute
			table : nama table yang ditunjuk oleh id
			value : jam berangkat
		}
	 */
	public function save($kogas, $json)
	{
		$table = $this->roTable();
		$db = $table->getAdapter();
		// delete old values
		$table->delete(sprintf('team_id = %s AND kogas = %s',
			$db->quote($this->_teamId),
			$db->quote($kogas)
		));
		// iterate new values
		foreach ($json as $obj) {
			$table->insert(array(
				'team_id' => $this->_teamId,
				'kogas' => $kogas,
				'nama_kelas' => $obj->class_name,
				'rute_id' => $obj->id,
				'waktu_berangkat' => $obj->value,
			));
		}
	}

	public function saveGabungan($json)
	{
		$table = $this->roGabunganTable();
		$db = $table->getAdapter();
		// delete old values
		$table->delete(sprintf('team_id = %s',
			$db->quote($this->_teamId)
		));
		// iterate new values
		foreach ($json as $obj) {
			$table->insert(array(
				'team_id' => $this->_teamId,
				'kogas' => $obj->kogas,
				'nama_kelas' => $obj->class_name,
				'rute_id' => $obj->id,
				'waktu_berangkat' => $obj->value,
			));
		}
	}
	
	/**
	 * Mengembalikan pengaturan RO yang disimpan
	 * @param string $kogas kogasgabla/kogasgabfib/etc
	 * @return array ('NAMA_KELAS' => 'WAKTU_BERANGKAT')
	 */
	public function retrieve($kogas)
	{
		$table = $this->roTable();
		$query = $table->select()
			->where('team_id = ?', $this->_teamId)
			->where('kogas = ?', $kogas)
		;
		$rowset = $table->fetchAll($query);
		$result = array();
		foreach ($rowset as $row) {
			$result[$row->nama_kelas][$row->rute_id] = $row->waktu_berangkat;
		}
		return $result;
	}

	public function retrieveGabungan($kogas)
	{
		$table = $this->roGabunganTable();
		$query = $table->select()
			->where('team_id = ?', $this->_teamId)
			->where('kogas = ?', $kogas)
		;
		$rowset = $table->fetchAll($query);
		$result = array();
		foreach ($rowset as $row) {
			$result[$row->nama_kelas][$row->rute_id] = $row->waktu_berangkat;
		}
		return $result;
	}
}