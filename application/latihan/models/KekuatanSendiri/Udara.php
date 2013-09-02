<?php
/**
 * Model untuk Kekuatan Sendiri - Udara
 *
 * @author Febi
 */

class Latihan_Model_KekuatanSendiri_Udara
{
	protected $_skenario;
	protected $_data;
	protected $_id;
	protected $_primary = 'id';

	/**
	 * @param App_Model_Crud $skenario object skenario parent dari kekuatan ini
	 * @param string diperlukan untuk CRUD
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
		return new Zend_Db_Table('latihan.kekuatan_sendiri_udara');
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

	/**
	 * Kembalikan array dengan nama key sesuai nama input field form
	 * @return array
	 */
	public function toFormArray()
	{
		return $this->_data;
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
	 * Mengembalikan semua kekuatan laut untuk skenario ini
	 * @return array
	 */
	public function allKekuatan()
	{
		$table = $this->table();
		$query = $table->select()
			->where('skenario_id = ?', $this->_skenario->getId())
		;
		$raw = $table->fetchAll($query);

		$result = array();
		foreach ($raw as $row) {
			$result[$row->kotama][] = $row->jumlah . '|' . $row->jenis;
		}
		return $result;
	}

	/**
	 * Mengembalikan array berbentuk [id] => "name"
	 * @return array
	 */
	public function BandaraAsOptions()
	{
		$table =  new Zend_Db_Table('public.bandara');
		$select = $table->select()->order('nama ASC');
		$raw = $table->fetchAll($select);

		$result = array('' => 'Pilih Pangkalan Udara');

		foreach ($raw as $row) {
			$result[$row['gid']] = $row['nama'];
		}
		return $result;
	}
}