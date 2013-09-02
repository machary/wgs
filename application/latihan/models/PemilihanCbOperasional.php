<?php
/**
 * Kogab memilih CB terbaik tiap kogas dalam timnya
 * @author Kanwil
 */
 
class Latihan_Model_PemilihanCbOperasional
{
	protected $_teamId;
	protected $_exists = false;
	protected $_chosen = array(
		'kogasgabla' => null,
		'kogasgabfib' => null,
		'kogasgabrat' => null,
		'kogasgabratmin' => null,
		'kogasgablinud' => null,
		'kogasgabhantai' => null,
		'kogasud' => null,
	);
	
	/**
	 * @param integer|string $teamId Mengacu ke user.logins:id_team
	 */
	public function __construct($teamId)
	{
		$this->_teamId = $teamId;
		// retrieve initial data
		$rowset = $this->table()->find($teamId);
		if ($rowset->count() > 0) {
			$this->exists = true;
			$this->_chosen = array_intersect_key($rowset->current()->toArray(), $this->_chosen);
		}
	}
	
	/**
	 * @return Zend_Db_Table Table utama yang digunakan
	 */
	public function table()
	{
		return new Zend_Db_Table('latihan.pilihan_cb_kogab');
	}
	
	/**
	 * @param string $key Nama kogas, kalau tidak diberi maka kembalikan semua
	 * @return array|string ID CB yang sudah dipilih
	 */
	public function chosen($key = null)
	{
		if (isset($key)) {
			return $this->_chosen[$key];
		}
		return $this->_chosen;
	}
	
	/**
	 * Mengeset cb terpilih dari data kiriman POST
	 * @param array $post Data yang dikirim
	 */
	public function setFromPost($post)
	{
		$this->_chosen = $post['cb'];
	}
	
	/**
	 * @return bool Apakah data sudah valid
	 */
	public function isValid()
	{
		return true;
	}
	
	/**
	 * Menyimpan informasi ke dalam database
	 */
	public function save()
	{
		$table = $this->table();
		if ($this->_exists) {
			// update
			$table->update($this->_chosen, $table->getAdapter()->quoteInto('team_id = ?', $this->_teamId));
		} else {
			// insert
			$toInsert = array('team_id' => $this->_teamId) + $this->_chosen;
			$table->insert($toInsert);
			$this->_exists = true;
		}
	}
	
	/**
	 * Mengembalikan data CB yang bisa dipilih dari masing-masing kogas
	 * @return array
	 */
	public function allChoices()
	{
		$result = array();
		$cbTable = new Zend_Db_Table('public.cb_operasional');
		// kogasgabla
		$query = $cbTable->select()
			->where('id_team = ?', $this->_teamId)
			->where('terpilih')
		;
		$result['kogasgabla'] = $cbTable->fetchAll($query);
		// @TODO kogas lainnya
		return $result;
	}
}