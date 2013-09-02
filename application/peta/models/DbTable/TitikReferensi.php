<?php
class Peta_Model_DbTable_TitikReferensi extends Zend_Db_Table_Abstract
{
    protected $_name = 'latihan.titik_referensi';
    protected $_tableName = 'latihan.titik_referensi';
    protected $_primary = 'id';

    public function getAlldata($limit, $offset, $sortColumn, $order, $filter, $search, $count, $id_divisi)
    {
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName)
            ->where("id_divisi = '".$id_divisi."'")
            ->where("id_login = '".$identity->id."'")
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'longitude';
            break;
            case 1: $string = 'latitude';
            break;
            case 2: $string = 'keterangan';
            break;
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
                    $query->where('longitude like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where('latitude like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 2 :
                    $query->where('keterangan like ' . "'" . '%'.$search.'%' . "'");
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

    public function selectTitikReferensi($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
                        ->from($this->_tableName)
                        ->where("id = '".$id."'")
        ;

        $result = $this->fetchRow($query);
        return (!empty($result)) ? $result->toArray() : null;
    }

    public function addTitikReferensi($value, $id_divisi)
    {
        $data = array(
            'id_divisi'     => $id_divisi,
            'keterangan'    => $value['keterangan'],
            'longitude'     => $value['longitude'],
            'latitude'      => $value['latitude'],
            'id_login'      => $value['id_login']
        );

        $this->insert($data);
    }

    public function updateTitikReferensi($value, $id)
    {
        $data = array(
            'keterangan'    => $value['keterangan'],
            'longitude'     => $value['longitude'],
            'latitude'      => $value['latitude']
        );
        $this->update($data, "id = '".$id."'");
    }

    public function deleteTitikReferensi($id)
    {
        $this->delete("id = '".$id."'");
    }

    public function getTitikReferensi($id_divisi)
    {
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $query = $this->select()->setIntegrityCheck(false)
                        ->from($this->_tableName)
                        ->where("id_divisi = '".$id_divisi."'")
                        ->where("id_login = '".$identity->id."'")
        ;
        $result = $this->fetchAll($query);

        return (!empty($result)) ? $result->toArray() : null;
    }
}