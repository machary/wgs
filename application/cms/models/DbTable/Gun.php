<?php
class Cms_Model_DbTable_Gun extends Zend_Db_Table_Abstract
{
    //protected $_scema = 'master';
    protected $_name = 'master.M_GUN';
    protected $_tableName = 'master.M_GUN';
    protected $_primary = 'GUN_ID';

    public function addGun($post)
    {
        $data = array(
            'GUN_NAME'                  => $post['GUN_NAME'],
            'GUN_CATEGORY'              => $post['GUN_CATEGORY'],
            'COUNTRY_ID'                => $post['COUNTRY_ID'],
            'GUN_CALIBER'               => $post['GUN_CALIBER'],
            'GUN_MAGAZINE_CAPACITY'     => $post['GUN_MAGAZINE_CAPACITY'],
            'GUN_RANGE_EFFECTIVE'       => $post['GUN_RANGE_EFFECTIVE']
        );
        $this->insert($data);
    }

    public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $filter = '', $search = '', $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->from(array('gun' => $this->_tableName),
                             array('gun.GUN_ID', 'gun.GUN_NAME', 'gun.GUN_CALIBER'))
                      ->join(array('negara' => 'master.M_COUNTRY'), 'negara."COUNTRY_ID" = gun."COUNTRY_ID"', array('negara.COUNTRY_NAME'))
                      ->join(array('gun_kat' => 'master.M_GUN_CATEGORY'), 'gun_kat."GUN_CATEGORY" = gun."GUN_CATEGORY"', array('gun_kat.GUN_CATEGORY_NAME'))
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'gun.GUN_ID';
                break;
            case 1: $string = 'negara.COUNTRY_NAME';
                break;
            case 2: $string = 'gun_kat.GUN_CATEGORY_NAME';
                break;
            case 3: $string = 'gun.GUN_NAME';
                break;
            case 4: $string = 'gun.GUN_CALIBER';
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
                    $query->where('gun."GUN_NAME" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where('gun."GUN_CALIBER" like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 2 :
                    $query->where('gun."COUNTRY_NAME" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 3 :
                    $query->where('gun."GUN_CATEGORY_NAME" like ' . "'" . '%'.$search.'%' . "'");
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

    public function cekName($data)
    {
        $query = $this->select()
                      ->from('master.M_GUN', array('master.M_GUN.GUN_NAME'))
                      ->where('master.M_GUN.GUN_NAME = ?', $data);

        $row = $this->fetchAll($query)->toArray();
        return count($row);
    }

    public function selectGun($id)
    {
        $query = $this->select()
                      ->from('master.M_GUN')
                      ->where('master."M_GUN"."GUN_ID" = ?', $id);
        $result = $this->fetchRow($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function updateGun($post, $id)
    {
        $data = array(
            'GUN_NAME'                  => $post['GUN_NAME'],
            'GUN_CATEGORY'              => $post['GUN_CATEGORY'],
            'COUNTRY_ID'                => $post['COUNTRY_ID'],
            'GUN_CALIBER'               => $post['GUN_CALIBER'],
            'GUN_MAGAZINE_CAPACITY'     => $post['GUN_MAGAZINE_CAPACITY'],
            'GUN_RANGE_EFFECTIVE'       => $post['GUN_RANGE_EFFECTIVE']
        );

        $this->update($data, 'master."M_GUN"."GUN_ID" = '.$id.'');
    }

    public function deleteGun($id)
    {
        $this->delete('master."M_GUN"."GUN_ID" = '.$id.'');
    }
}