<?php
/**
 * Model untuk Kekuatan Sendiri - Udara Detail
 *
 * @author Febi
 */

class Latihan_Model_KekuatanSendiri_UdaraDetail
{
	protected $_parent_id;
	protected $_data;
	protected $_tableName = 'latihan.kekuatan_sendiri_udara_detail';
	protected $_thisTable;

	/**
	 * @param App_Model_Crud $parent_id object parent dari kekuatan ini
	 * @param string $parent_id diperlukan untuk CRUD
	 */
	public function __construct( $parent_id)
	{
		$this->_parent_id = $parent_id;
		$dbTable = new Zend_Db_Table('latihan.kekuatan_sendiri_udara_detail');
	}

	public function table()
	{
		return new Zend_Db_Table('latihan.kekuatan_sendiri_udara_detail');
	}

	/**
	 * Mengembalikan seluruh data
	 * @return Zend_Db_Table_Rowset
	 */
	public function all()
	{
		if($this->_parent_id)
		{
			$table = $this->table();
			$query = $table->select()->setIntegrityCheck(false)->from(array('ud' => 'latihan.kekuatan_sendiri_udara_detail'))
				->join(array( 'st' => 'master.simbol_taktis'), 'ud.taktis_id = st.id', 'jenis')
				->where( 'ud.parent_id = ?', $this->_parent_id)
			;
			return $table->fetchAll($query);
		}
		else
			return null;
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
	 * Menyimpan ke database
	 */
	public function save()
	{

		$table = $this->table();
		$db = $table->getAdapter();

		// hapus yg tidak tersimpan
		$savedIds = array();
		foreach ($this->_data as $row) {
			if ($row['id']) {
				$savedIds[] = $row['id'];
			}
		}
		$delQuery = array(
			$db->quoteInto('parent_id = ?', $this->_parent_id),
		);
		if (count($savedIds)) {
			$delQuery[] = $db->quoteInto('id NOT IN (?)', $savedIds);
		}

		if($this->_parent_id)
		{
			$table->delete($delQuery);
		}


		// insert dan update
		foreach ($this->_data as $row) {
			if ($row['id']) {
				$where = $table->getAdapter()->quoteInto('id = ?', $row['id']);
				unset($row['id']);
				$table->update($row, $where);
			} else {
				unset($row['id']);
				$table->insert($row);
			}
		}
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

	public function mydelete()
	{

		$this->table()->delete('parent_id = '.$this->_parent_id);
	}
}