<?php
class Cms_Model_DbTable_Home extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.berita';
    protected $_tableName = 'master.berita';
    protected $_primary = 'id';

    public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $filter = '', $search = '', $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from($this->_tableName);

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'judul';
            break;
            case 1: $string = 'tanggal';
        }

        if((strtolower($order) == 'asc') || (strtolower($order) == 'undefined'))
        {
            $string .= ' ASC';
        }
        else
        {
            $string .= ' DESC';
        }

        if($filter != '' && $search != '')
        {
            switch($filter)
            {
                case 0 :
                    $query->where('judul like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where('tanggal like ' ."'". '%'.$search.'%' . "'");
                    break;
            }
        }

        $query->order($string);

        if( $count == false ) {

            $query->limit( $limit, $offset );
            $result = $this->fetchAll( $query );

            if( empty( $result ) ) return false;
            return $result->toArray();

        } else {

            $result = $this->fetchAll( $query );

            if( empty( $result ) ) return false;
            return count( $result );

        }
    }

    public  function countAll()
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName);

        $result = $this->fetchAll( $query );

        if( empty( $result ) ) return null;
        return count($result->toArray());
    }

    public function addBerita($post)
    {
        $data = array(
            'judul' => $post['judul'],
            'tanggal' => $post['tanggal'],
            'berita' => $post['berita'],
        );
        $this->insert($data);
    }

    public function getByID($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName)
            ->where( 'id = ?', $id);

        $result = $this->fetchRow( $query );

        if( empty( $result ) ) return null;
        return $result->toArray();
    }

    public function del($key = null, $val = null)
    {
        if( empty($key) && empty($val) ) return false;
        try {
            $condition = array( $key .' = ?' => $val);
            $this->delete( $condition );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}