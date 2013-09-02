<?php
/**
 * Model Dasar untuk rute (darat/laut/udara/marinir/linud)
 * Struktur table harus sama untuk menggunakan model ini
 * 
 * @author Kanwil
 */

abstract class Ops_Model_Rute
{
	protected $_id;
	protected $_cbId;
	protected $_row;
	
	protected $_titik; // array
	protected $_formasi; // array
	protected $_rudal; // array
	
	protected $_cb = null;
	
	// OVERRIDE THESE
	protected static $_cbTableName = '';
	protected static $_ruteTableName = '';
	protected static $_formasiTableName = '';
	protected static $_titikTableName = '';
	protected static $_durasiColumn = ''; // nama kolom di table CB yg menyimpan durasi rute ini
	
	/**
	 * @param integer|string $cbId CB ID
	 * @param integer|string $id primary key
	 */
	public function __construct($cbId, $id = null)
	{
		$this->_cbId = $cbId;
		if (isset($id)) {
			// coba cari 
			$table = $this->table();
			$rowset = $table->find($id);
			if (count($rowset) > 0) {
				$this->_id = $id;
				$this->_row = $rowset->current();
			} else {
				$this->_id = null;
				$this->_row = $table->createRow();
			}
		} else {
			$this->_id = null;
			$this->_row = $this->table()->createRow();
		}
	}
	
	/**
	 * Mengembalikan semua simbol kapal
	 * @return Zend_Db_Table_Rowset
	 */
	abstract public function symbols();
	
	public function table()
	{
		return new Zend_Db_Table(static::$_ruteTableName);
	}
	
	public function tableTitik()
	{
		return new Zend_Db_Table(static::$_titikTableName);
	}
	
	public function tableFormasi()
	{
		return new Zend_Db_Table(static::$_formasiTableName);
	}

	public function tableRudal()
	{
		return new Zend_Db_Table(static::$_rudalTableName);
	}
	
	/**
	 * @return Zend_Db_Table_Row CB Operasional
	 */
	public function cb()
	{
		if (!isset($this->_cb)) {
			$table = new Zend_Db_Table(static::$_cbTableName);
			$this->_cb = $table->find($this->_cbId)->current();
		}
		return $this->_cb;
	}
	
	public function exists()
	{
		return isset($this->_id);
	}
	
	// *** GETTER ***
	public function getId() {return $this->_id;}
	public function getCbId() {return $this->_cbId;}
	public function getRow() {return $this->_row;}
	
	public function getTitik() {
		if ($this->exists() && !isset($this->_titik)) {
			$table = $this->tableTitik();
			$query = $table->select()
				->where('rute_id = ?', $this->_id)
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
				->where('rute_id = ?', $this->_id)
				->order('urutan ASC')
			;
			$this->_formasi = $table->fetchAll($query)->toArray();
		}
		return $this->_formasi;
	}

	public function getRudal() {
		if ($this->exists() && !isset($this->_rudal)) {
			$table = $this->tableRudal();
			$query = $table->select()
				->where('rute_id = ?', $this->_id)
				->order('urutan_formasi ASC')
				->order('rudal_id ASC')
			;
			$this->_rudal = $table->fetchAll($query)->toArray();
		}
		return $this->_rudal;
	}

	public function getRudalFormasi($id) {
        $table = $this->tableRudal();
        $query = $table->select()
            ->where('rute_id = ?', $this->_id)
            ->where('urutan_formasi = ?', $id)
            ->order('urutan_formasi ASC')
            ->order('rudal_id ASC')
        ;

        $result = $table->fetchAll($query);

        if( empty( $result ) ) return false;
        return $result->toArray();
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
		$this->_row->nama = $post['nama'];
		$this->_row->durasi = $post['durasi'];
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

		if (isset($post['rudalData'])) {
			$this->_rudal = json_decode($post['rudalData']);
		} else {
			$this->_rudal = array();
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
		$this->_row->cb_id = $this->_cbId;

		$this->_row->save();

		if (!$this->exists()) {
			$this->_id = $this->_row->id;
		}
		
		// table titik
		$table = $this->tableTitik();
		// hapus titik lama
		$table->delete($db->quoteInto('rute_id = ?', $this->_id));
		// simpan titik baru
		$i = 1;
		foreach ($this->_titik as $titik) {
			$row = array(
				'rute_id' => $this->_id,
				'urutan' => $i++,
				'nama' => $titik['nama'],
				'longitude' => $titik['longitude'],
				'latitude' => $titik['latitude'],
				'kecepatan' => $titik['kecepatan'],
				'delay' => $titik['delay'],
			);
			$table->insert($row);
		}

        // table formasi
		$table = $this->tableFormasi();
		// hapus formasi lama
		$table->delete($db->quoteInto('rute_id = ?', $this->_id));
		// simpan formasi baru
		$i = 1;
		foreach ($this->_formasi as $formasi) {
			$row = array(
				'rute_id' => $this->_id,
				'urutan' => $i++,
				'simbol_taktis' => $formasi['simbol_taktis'],
				'x' => $formasi['x'],
				'y' => $formasi['y'],
				'singkatan' => $formasi['singkatan'],
			);
			$table->insert($row);
		}

        // table rudal
		$table = $this->tableRudal();
		// hapus formasi lama
		$table->delete($db->quoteInto('rute_id = ?', $this->_id));
		// simpan formasi baru
		$i = 1;

		foreach ($this->_rudal as $rudalIndex => $rudalData) {
            if(!empty($rudalData))
            {
                foreach($rudalData as $rudalDetailIndex => $rudalDetail) {
                    if(!empty($rudalDetail))
                    {
                        $row = array(
                            'rute_id' => $this->_id,
                            'urutan_formasi' => $rudalIndex,
                            'rudal_id' => $rudalDetailIndex,
                            'radius' => $rudalDetail->radius,
                            'kecepatan' => $rudalDetail->kecepatan,
                            'longitude_start' => $rudalDetail->lonStart,
                            'latitude_start' => $rudalDetail->latStart,
                            'longitude_target' => $rudalDetail->lonTarget,
                            'latitude_target' => $rudalDetail->latTarget
                        );
                        $table->insert($row);
                    }
                }
            }
		}
		// lastly: update durasi cb
		static::updateDurasi($this->_cbId);
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
		static::updateDurasi($this->_cbId);
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
	
	/**
	 * Mengembalikan satuan kecepatan yang dipakai oleh rute ini
	 * @return string kmph/knot
	 */
	public function speedUnit()
	{
		// laut = knot, darat/udara/linud/marinir = kmph
		if (preg_match('/_laut$/', static::$_durasiColumn)) {
			return 'knot';
		} else {
			return 'kmph';
		}
	}
	
	// ******************************************************
	//                    STATIC METHODS
	// ******************************************************
	
	/**
	 * Mengembalikan seluruh rute milik suatu CB Operasional dalam bentuk row
	 * @param integer|string $cbId 
	 * @return Zend_Db_Table_Rowset
	 */
	public static function allRows($cbId)
	{
		$table = new Zend_Db_Table(static::$_ruteTableName);
		$query = $table->select()
			->where('cb_id = ?', $cbId)
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
		$rows = static::allRows($cbId);
		$return = array();
		$className = get_called_class(); // late static binding
		foreach ($rows as $row) {
			$return[] = new $className($cbId, $row->id);
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
		$table = new Zend_Db_Table(static::$_cbTableName);
		$row = $table->find($cbId)->current();
		if ($row) {
			// retrieve max durasi
			$db = $table->getAdapter();
			$query = $db->select()
				->from(static::$_ruteTableName, array('max_durasi' => 'MAX(durasi)'))
				->where('cb_id = ?', $cbId)
			;
			$maxDurasi = $db->fetchOne($query);
			// update value
			$col = static::$_durasiColumn;
			$row->$col = $maxDurasi;
			$row->save();
		}
	}

}

