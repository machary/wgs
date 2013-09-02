<?php
/**
 */

class Peta_Model_Pangkalan
{
	protected $_primary = 'gid';
	protected $_id = null;

	public function __construct($id = null)
	{
        $this->_id = $id;
	}
	
	/**
	 * @return Zend_Db_Table tempat menyimpan kekuatan musuh
	 */
	public function table()
	{
		return new Zend_Db_Table('public.lanal');
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
        $query = $table->select();

        if( !is_null($this->_id) ){
            $query->where( $this->_primary. '= ?', $this->_id );
        }
        return $table->fetchAll($query);
	}

    /**
   	 * Mengembalikan seluruh data
   	 * @return Zend_Db_Table_Rowset
   	 */
   	public function get()
   	{
           $table = $this->table();
           $query = $table->select();

           if( !is_null($this->_id) ){
               $query->where( $this->_primary. '= ?', $this->_id );
           }
           return $table->fetchRow($query);
   	}
}