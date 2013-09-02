<?php
/**
 * Kogab memilih CB terbaik tiap kogas
 *
 * @author Kanwil
 */
 
class Ops_Model_Kogab
{
	protected $_teamId; // primary key
	protected $_row = null;
	protected $_rowName = array();
	
	protected $_options = null;
	
	// which column refers to which table
	protected $_ref = array(
		'kogasud' => 'ops.ud_cb',
		'kogasgabla' => 'ops.gabla_cb',
		'kogasgabfib' => 'ops.gabfib_cb',
		'pasrat' => 'ops.pasrat_cb',
		'kogasgablinud' => 'ops.gablinud_cb',
		'kogasgabrat' => 'ops.gabrat_cb',
	);
	
	/**
	 * @param string|int $teamId memilih CB terbaik milik tim ini
	 */
	public function __construct($teamId)
	{
		$this->_teamId = $teamId;
		$table = $this->table();
		$rowset = $table->find($teamId);
		if (count($rowset) > 0) {
			$this->_row = $rowset->current();

            foreach ($this->_ref as $col => $tableName) {
    			if($this->_row->$col)
                {
                    $thistable = new Zend_Db_Table($tableName);
                    $rowNameSet = $thistable->find($this->_row->$col)->current();

                    $colname = $col.'-rename';
                    $this->_rowName[$colname] = $rowNameSet->nomor_ro;
                }
                else
                {
                    $colname = $col.'-rename';
                    $this->_rowName[$colname] = '';
                }

    		}

		} else {
			$this->_row = $table->createRow();
			$this->_row->team_id = $teamId;
		}
	}
	
	public function table()
	{
		return new Zend_Db_Table('latihan.pilihan_cb_kogab');
	}
	
	public function getRow() 
	{
		return $this->_row;
	}

	public function getName()
	{
		return $this->_rowName;
	}
	
	public function getOptions()
	{
		if (!isset($this->_options)) {
			foreach ($this->_ref as $col => $tableName) {
				$table = new Zend_Db_Table($tableName);
				$query = $table->select()
					->where('team_id = ?', $this->_teamId)
					->where('terpilih')
				;
				$this->_options[$col] = $table->fetchAll($query);
			}
		}
		return $this->_options;
	}
	
	/**
	 * Set parameters and validate input
	 * @param array $post
	 * @return bool
	 */
	public function isValid($post)
	{
		// @TODO
        foreach ($this->_ref as $col => $tableName) {
			$this->_row->$col = (isset($post[$col])) ? $post[$col] : null;
            $colname = $col.'-rename';
            if(isset($post[$colname]))
            {
                $this->_rowName[$colname] = $post[$colname];
            }
		}

		return true;
	}
	
	/**
	 * Save data to database
	 */
	public function save()
	{
		$this->_row->save();

        foreach ($this->_ref as $col => $tableName) {
            if($this->_row->$col)
            {
                $colname = $col.'-rename';
                if($this->_rowName[$colname])
                {
                    $thistable = new Zend_Db_Table($tableName);
                    $rowset = $thistable->find($this->_row->$col)->current();

                    $rowset->nomor_ro = $this->_rowName[$colname];
                    $rowset->save();
                }
            }
        }
	}

}