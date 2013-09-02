<?php

/**
 * @author tajhul.faijin@sangkuriang.co.id
 */

class Cms_Model_KesatuanMarinir
{
    //field
    protected $_id;

    //object properties
    protected $_primary = 'id';
    protected $_table = null;
    protected $_data = null;
    protected $_divisi = null;

    public function __construct($id = null)
    {
        $this->_id = $id;
    }

    /**
     * Apakah kesatuan ini ada di database?
     * @return bool
     */
    public function exists()
    {
        return $this->_id !== null;
    }

    /**
     * Mengembalikan kondisi where untuk object ini
     */
    public function where()
    {
        $db = $this->table()->getAdapter();
        return $db->quoteInto("\"{$this->_primary}\" = ?", $this->_id);
    }

    /**
     * Mengembalikan object dbtable utama kesatuan marinir
     * @return Zend_Db_Table
     */
    public function table()
    {
        return new Zend_Db_Table('master.kesatuan_marinir');
    }

    //
    public function setFromPost( $data )
    {
        $this->_data = $data;
    }

    /**
     * Simpan penambahan/perubahan ke database
     */
    public function save()
    {
        $table = $this->table();
        if ($this->exists()) {
            $table->update($this->_data, $this->where());
        } else {
            $table->insert( $this->_data );
            $this->_id = $table->getAdapter()->lastInsertId();
            return $this->_id;
        }
    }

    public function fetchRow()
    {
        $table = $this->table();
        $query = $table->select()->setIntegrityCheck(false)
            ->from('master.kesatuan_marinir', array('*'))
        ;
        $query->where($this->where());

        $result = $table->fetchRow($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    /**
     * Hapus kesatuan ini
     */
    public function delete()
    {
        if (!$this->exists()) return;
        $table = $this->table();
        $table->delete($this->where());
        $this->_id = null;
    }

}