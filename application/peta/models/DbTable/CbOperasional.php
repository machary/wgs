<?php
class Peta_Model_DbTable_CbOperasional extends Zend_Db_Table_Abstract
{
    protected $_name = 'cb_operasional';
    protected $_tableName = 'cb_operasional';
    protected $_primary = 'id';

    public function getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->from($this->_tableName)
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = $this->_tableName.'.no_cb_operasional';
            break;
            case 1: $string = $this->_tableName.'.waktu_pembuatan';
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
                    $query->where($this->_tableName.'."no_cb_operasional" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where($this->_tableName.'."waktu_pembuatan" like ' ."'". '%'.$search.'%' . "'");
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

    public function addCb($post, $id_team)
    {
        $data = array(
            'no_cb_operasional' => $post['no_cb_operasional'],
            'waktu_pembuatan'   => $post['waktu_pembuatan'],
            'hidden_time'       => 'now()',
            'kotama'            => $post['kotama'],
            'pangkalan_aju'     => $post['pangkalan_aju'] ? $post['pangkalan_aju'] : null,
            'id_team'           => $id_team
        );

        $this->insert($data);
    }

    public function updateCb($post, $id, $id_team)
    {
        $data = array(
            'no_cb_operasional' => $post['no_cb_operasional'],
            'waktu_pembuatan'   => $post['waktu_pembuatan'],
            'hidden_time'       => 'now()',
            'kotama'            => $post['kotama'],
            'pangkalan_aju'     => $post['pangkalan_aju'] ? $post['pangkalan_aju'] : null,
            'id_team'           => $id_team
        );

        $this->update($data, "id = '".$id."'");
    }

    public function getCb($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->from($this->_tableName)
                      ->where("id = '".$id."'")
        ;

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
}