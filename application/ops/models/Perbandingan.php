<?php
/**
 * Memilih up to 3 CB terbaik
 * 
 * @author Kanwil
 */

class Ops_Model_Perbandingan
{
	protected $_cbTableName = ''; // must be overriden
	
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
		return new Zend_Db_Table($this->_cbTableName);
	}
	
	/**
	 * Mengembalikan daftar CB yang dimiliki team
	 * @return Zend_Db_Table_Rowset
	 */
	public function allCb()
	{
		$table = $this->cbTable();
		// filter team
		$where = $table->select()
			->where('team_id = ?', $this->_teamId)
			->order('nomor')
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
		$where = $table->getAdapter()->quoteInto('team_id = ?', $this->_teamId);
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

    public function allPanglimaCb()
    {
        $table = $this->cbTable();
        // filter team
        $where = $table->select()
            ->where('team_id = ?', $this->_teamId)
            ->where('status_panglima = true')
            ->order('nomor')
        ;
        return $table->fetchAll($where);
    }

    public function allNotPanglimaCb()
    {
        $table = $this->cbTable();
        // filter team
        $where = $table->select()
            ->where('team_id = ?', $this->_teamId)
            ->where('status_panglima = false')
            ->order('nomor')
        ;
        return $table->fetchAll($where);
    }
}