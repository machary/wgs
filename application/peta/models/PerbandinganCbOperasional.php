<?php
/**
 * Model untuk menangani perbandingan cb operasional
 * Sekaligus memilih up to 3 CB terbaik
 * 
 * @author Kanwil
 */

class Peta_Model_PerbandinganCbOperasional
{
	protected $_teamId;
	
	/**
	 * @param string|int $teamId membandingkan CB milik tim ini
	 */
	public function __construct($teamId)
	{
		$this->_teamId = $teamId;
	}
	
	// === GETTER DB_TABLE ===
	public function cbTable()
	{
		return new Zend_Db_Table('public.cb_operasional');
	}
	
	/**
	 * Mengembalikan daftar CB yang dimiliki team
	 * @return array|Zend_Db_Table_Rowset
	 */
	public function allCb()
	{
		$table = $this->cbTable();
		// filter team
		$where = $table->select()
			->where('id_team = ?', $this->_teamId)
			->order('no_cb_operasional')
		;
		return $table->fetchAll($where);
	}
	
	/**
	 * Menyimpan cb mana saja yang terpilih untuk diajukan ke kogab
	 * @param array $ids Id cb operasional
	 */
	public function saveCbTerpilih($ids)
	{
		// @TODO pilih max 3 cb
		$table = $this->cbTable();
		// filter team
		$where = $table->getAdapter()->quoteInto('id_team = ?', $this->_teamId);
		// reset jadi false semua
		$table->update(array(
			'terpilih' => new Zend_Db_Expr('false'),
		), $where);
		// simpan yg terpilih
		if ($ids) {
			$whereIn = $table->getAdapter()->quoteInto('id IN (?)', $ids);
			$table->update(array(
				'terpilih' => new Zend_Db_Expr('true'),
			), $whereIn);
		}
	}
}