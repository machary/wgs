<?php
class Peta_Model_DbTable_Operasional extends Zend_Db_Table_Abstract
{
    protected $_name = 'decisive_operasional';
    protected $_tableName = 'decisive_operasional';
    protected $_primary = 'id';

    public function adddecisive($post, $cb)
    {
        $data = array(
            //'id_target'         => $post['id_target'],
            'longitude'         => $post['longitude'],
            'latitude'          => $post['latitude'],
            'the_geom'          => new Zend_Db_Expr("ST_GeomFromText('POINT(".$post['longitude']." ".$post['latitude'].")',4326)"),
            'keterangan'        => $post['keterangan'],
            'no_cb_operasional' => $cb,
            'id_cb'             => $post['id']
        );

        $this->insert($data);
    }

    public function selectDecisive()
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->from($this->_tableName, array('id', 'no_cb_operasional'))
        ;

        $result = $this->fetchAll($query);
        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count, $no_cb)
    {
//        $query = $this->select()->setIntegrityCheck(false)
//            ->from(array('dec' => $this->_tableName))
//            ->join(array('intel' => 'intelijen_poly'), 'intel."id" = dec."id_target"', array('*'))
//            ->where('dec."no_cb_operasional" = ' . "'" .$no_cb. "'")
//        ;

        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('dec' => $this->_tableName), array( 'id_dec' => 'dec.id', 'dec.id_target', 'dec.longitude','dec.latitude', 'dec.the_geom', 'dec.keterangan','dec.no_cb_operasional', 'dec.id_cb'))
            ->joinLeft(array('intel' => 'intelijen_poly'), 'intel."id" = dec."id_target"', array('*'))
            ->where('dec."no_cb_operasional" = ' . "'" .$no_cb. "'")
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0:
                $string = 'dec.no_cb_operasional';
                break;
            case 1:
                $string = 'intel.sandi_operasi';
                break;
            case 2:
                $string = 'dec.longitude';
                break;
            case 3:
                $string = 'dec.latitude';
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
                    $query->where('dec."no_cb_operasional" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where('intel."sandi_operasi" like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 2 :
                    $query->where('dec."longitude" like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 3 :
                    $query->where('dec."latitude" like ' ."'". '%'.$search.'%' . "'");
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

    public function removeDecisive($id)
    {
        $this->delete("id = '".$id."'");
    }

    public function updateCb($post)
    {
        $data = array(
            'no_cb_operasional' => $post['no_cb_operasional']
        );

        $this->update($data, "id_cb = '".$post['id']."'");
    }
}