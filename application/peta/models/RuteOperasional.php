<?php
/**
 * Menangani Rute Tiap CB Operasional
 * UPDATE: khusus Kogasgabla operasi Laut
 *
 * Rute terdiri atas Nama, 1 Kumpulan Titik Rute, dan 1 Formasi
 * Table yang terlibat:
	- latihan.operasional_rute
	- latihan.operasional_rute_titik
	- latihan.operasional_rute_formasi
 * 
 * @author Kanwil
 */
 
class Peta_Model_RuteOperasional
{
	protected $_id;
	protected $_cbOperasionalId;
	protected $_nama;
	protected $_durasi;
	protected $_latum;
	
	protected $_titik; // array
	protected $_formasi; // array
	
	protected $_hasLatum = true;
	protected $_cb = null;
	protected $_seq = 'latihan.operasional_rute_id_seq'; // HARDCODED!
	
	/**
	 * @param integer|string $cbId cb_operasional_id
	 * @param integer|string $id latihan.operasional_rute.id
	 */
	public function __construct($cbId, $id = null)
	{
		$this->_cbOperasionalId = $cbId;
		if (isset($id)) {
			// coba cari 
			$table = $this->table();
			$rowset = $table->find($id);
			if (count($rowset) > 0) {
				$this->_id = $id;
				// set atribut lain
				$this->_nama = $rowset->current()->nama;
				$this->_durasi = $rowset->current()->durasi;
				if ($this->_hasLatum) {
					$this->_latum = $rowset->current()->latum;
				}
			} else {
				$this->_id = null;
			}
		} else {
			$this->_id = null;
		}
	}
	
	public function table()
	{
		return new Zend_Db_Table('latihan.operasional_rute');
	}
	
	public function tableTitik()
	{
		return new Zend_Db_Table('latihan.operasional_rute_titik');
	}
	
	public function tableFormasi()
	{
		return new Zend_Db_Table('latihan.operasional_rute_formasi');
	}
	
	/**
	 * @return Zend_Db_Table_Row CB Operasional
	 */
	public function cb()
	{
		if (!isset($this->_cb)) {
			$table = new Zend_Db_Table('public.cb_operasional');
			$this->_cb = $table->find($this->_cbOperasionalId)->current();
		}
		return $this->_cb;
	}
	
	public function exists()
	{
		return isset($this->_id);
	}
	
	// *** GETTER ***
	public function getCbId() {return $this->_cbOperasionalId;}
	public function getId() {return $this->_id;}
	public function getNama() {return $this->_nama;}
	public function getDurasi() {return $this->_durasi;}
	public function getLatum() {return $this->_latum;}
	public function getTitik() {
		if ($this->exists() && !isset($this->_titik)) {
			$table = $this->tableTitik();
			$query = $table->select()
				->where('operasional_rute_id = ?', $this->_id)
				->order('urutan ASC')
			;
			$this->_titik = $table->fetchAll($query)->toArray();
		}
		return $this->_titik;
	}
	public function getFormasi() {
		if ($this->exists() && !isset($this->_formasi)) {
			$table = $this->tableFormasi();
			$query = $table->select()
				->where('operasional_rute_id = ?', $this->_id)
				->order('urutan ASC')
			;
			$this->_formasi = $table->fetchAll($query)->toArray();
		}
		return $this->_formasi;
	}
	
	/**
	 * Mengecek kevalidan data sekaligus meng-assign nilai
	 * @param array $post data dikirim dari html
	 * @return bool true jika data sudah dapat disimpan, false kalau tidak
	 */
	public function isValid($post)
	{
		// @TODO validasi
		// parse untuk rute
		$this->_nama = $post['nama'];
		$this->_durasi = $post['durasi'];
		if ($this->_hasLatum) {
			$this->_latum = $post['latum'];
		}
		// parse untuk titik
		if (isset($post['titik'])) {
			// data yg dikirim berbentuk titik[nama][0..n], titik[longitude][0..n], dst
			// ubah ke bentuk titik[0..n][nama/longitude/etc]
			// asumsi format pengiriman sudah benar
			$titik = array();
			foreach ($post['titik'] as $column => $atemp) {
				foreach ($atemp as $i => $val) {
					$titik[$i][$column] = $val;
				}
			}
			$this->_titik = $titik;
		} else {
			$this->_titik = array();
		}
		// parse untuk formasi
		if (isset($post['formasi'])) {
			$this->_formasi = $post['formasi'];
		} else {
			$this->_formasi = array();
		}
		return true;
	}
	
	/**
	 * Menyimpan informasi rute ke database
	 */
	public function save()
	{
		// table rute
		$table = $this->table();
		$db = $table->getAdapter();
		$row = array(
			'cb_operasional_id' => $this->_cbOperasionalId,
			'nama' => $this->_nama,
			'durasi' => $this->_durasi,
		);
		if ($this->_hasLatum) {
			$row['latum'] = $this->_latum;
		}
		if ($this->exists()) {
			$table->update($row, $db->quoteInto('id = ?', $this->_id));
		} else {
			$table->insert($row);
			$this->_id = $db->lastSequenceId($this->_seq);
		}
		// table titik
		$table = $this->tableTitik();
		// hapus titik lama
		$table->delete($db->quoteInto('operasional_rute_id = ?', $this->_id));
		// simpan titik baru
		$i = 1;
		foreach ($this->_titik as $titik) {
			$row = array(
				'operasional_rute_id' => $this->_id,
				'urutan' => $i++,
				'nama' => $titik['nama'],
				'longitude' => $titik['longitude'],
				'latitude' => $titik['latitude'],
				'kecepatan' => $titik['kecepatan'],
			);
			$table->insert($row);
		}
			
		// table formasi
		$table = $this->tableFormasi();
		// hapus formasi lama
		$table->delete($db->quoteInto('operasional_rute_id = ?', $this->_id));
		// simpan formasi baru
		$i = 1;
		foreach ($this->_formasi as $formasi) {
			$row = array(
				'operasional_rute_id' => $this->_id,
				'urutan' => $i++,
				'simbol_taktis' => $formasi['simbol_taktis'],
				'x' => $formasi['x'],
				'y' => $formasi['y'],
				'singkatan' => $formasi['singkatan'],
			);
			$table->insert($row);
		}
		// lastly: update durasi cb
		static::updateDurasi($this->_cbOperasionalId);
	}
	
	/**
	 * Menghapus satu rute (termasuk titik dan formasi yg terhubung)
	 */
	public function delete()
	{
		$table = $this->table();
		$table->delete($table->getAdapter()->quoteInto('id = ?', $this->_id));
		// titik dan table diurus oleh foreign constraint postgresql
		// lastly: update durasi cb
		self::updateDurasi($this->_cbOperasionalId);
	}
	
	/**
	 * Mengembalikan semua simbol kapal
	 * @return array|Zend_Db_Table_Rowset
	 */
	public function symbols()
	{
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis = ?', 'kapal'));
	}
	
	/**
	 * Mengembalikan informasi mengenai pangkalan aju CB, jika ada
	 * @return null|array array(nama,x,y), Null jika tidak memiliki pangkalan aju atau tidak ada informasi koordinatnya
	 */
	public function pangkalanAju()
	{
		$cb = $this->cb();
		if ($cb->pangkalan_aju) {
			$db = $this->table()->getAdapter();
			$query = $db->select()
				->from(array('mp' => 'master.pangkalan'))
				->join(array('pl' => 'public.lanal'), 'pl.id_master = mp.idpangkalan', array('x','y'))
				->where('mp.idpangkalan = ?', $cb->pangkalan_aju)
			;
			$row = $db->fetchRow($query);
			return $row ? $row : null;
		}
		return null;
	}
	
	// ******************************************************
	//                    STATIC METHODS
	// ******************************************************
	
	/**
	 * Mengembalikan seluruh rute milik suatu CB Operasional dalam bentuk row
	 * @param integer|string $cbId 
	 * @return array of Zend_Db_Table_Row
	 */
	public static function allRows($cbId)
	{
		$table = new Zend_Db_Table('latihan.operasional_rute');
		$query = $table->select()
			->where('cb_operasional_id = ?', $cbId)
			->order('nama ASC')
		;
		return $table->fetchAll($query);
	}
	
	/**	
	 * Mengembalikan seluruh rute milik suatu CB Operasional dalam bentuk instance model ini
	 * @param integer|string $cbId 
	 * @return array of Peta_Model_RuteOperasional
	 */
	public static function allObjects($cbId) 
	{
		$rows = self::allRows($cbId);
		$return = array();
		foreach ($rows as $row) {
			$return[] = new Peta_Model_RuteOperasional($cbId, $row->id);
		}
		return $return;
	}
	
	/**
	 * Mengupdate nilai kolom "durasi" bagi suatu CB Operasional 
	 * @param integer|string $cbId 
	 */
	public static function updateDurasi($cbId)
	{
		// retrieve cb row
		$table = new Zend_Db_Table('public.cb_operasional');
		$row = $table->find($cbId)->current();
		if ($row) {
			// retrieve max durasi
			$db = $table->getAdapter();
			$query = $db->select()
				->from('latihan.operasional_rute', array('max_durasi' => 'MAX(durasi)'))
				->where('cb_operasional_id = ?', $cbId)
			;
			$maxDurasi = $db->fetchOne($query);
			// update value
			$row->durasi = $maxDurasi;
			$row->save();
		}
	}
}