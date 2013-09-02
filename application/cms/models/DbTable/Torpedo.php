<?php
class Cms_Model_DbTable_Torpedo Extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.M_TORPEDO';
    protected $_tableName = 'master.M_TORPEDO';
    protected $_primary = 'TORPEDO_ID';

    public function getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false)
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->distinct()
                      ->from(array('tor' => $this->_tableName), array('tor.TORPEDO_ID', 'tor.TORPEDO_NAME', 'tor.TORPEDO_WEIGHT',
                                'tor.TORPEDO_DIAMETERS', 'tor.TORPEDO_MAX_SPEED', 'tor.TORPEDO_MAX_RANGE', 'tor.TORPEDO_PROB_OF_HIT'))
                      ->join(array('tipe' => 'master.M_DETECT_TYPE'), 'tipe."DETECT_TYPE" = tor."DETECT_TYPE"', array('tipe.DETECT_TYPE_NAME'))
                      ->join(array('negara' => 'master.M_COUNTRY'), 'negara."COUNTRY_ID" = tor."COUNTRY_ID"', array('negara.COUNTRY_NAME'))
                      ->join(array('launch' => 'master.M_LAUNCH_TYPE'), 'launch."LAUNCH_TYPE" = tor."LAUNCH_TYPE"', array('launch.LAUNCH_TYPE_NAME'))
        ;

        switch($sortColumn)
        {
            case 0: $string = 'tor.TORPEDO_NAME';break;
            case 1: $string = 'negara.COUNTRY_NAME';break;
            case 2: $string = 'tipe.DETECT_TYPE_NAME';break;
            case 3: $string = 'launch.LAUNCH_TYPE_NAME';break;
            case 4: $string = 'tor.TORPEDO_WEIGHT';break;
            case 5: $string = 'tor.TORPEDO_DIAMETERS';break;
            case 6: $string = 'tor.TORPEDO_MAX_SPEED';break;
            case 7: $string = 'tor.TORPEDO_MAX_RANGE';break;
            case 8: $string = 'tor.TORPEDO_PROB_OF_HIT';break;
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
                    $query->where('tor."TORPEDO_NAME" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where('negara."COUNTRY_NAME" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 2 :
                    $query->where('tipe."DETECT_TYPE_NAME" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 3 :
                    $query->where('launch."LAUNCH_TYPE_NAME" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 4 :
                    $query->where('tor."TORPEDO_WEIGHT" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 5 :
                    $query->where('tor."TORPEDO_DIAMETERS" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 6 :
                    $query->where('tor."TORPEDO_MAX_SPEED" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 7 :
                    $query->where('tor."TORPEDO_MAX_RANGE" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 8 :
                    $query->where('tor."TORPEDO_PROB_OF_HIT" like ' . "'" . '%'.$search.'%' . "'");
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

    public function addTorpedo($post)
    {
        $data = array(
            'TORPEDO_NAME'          => $post['TORPEDO_NAME'],
            'COUNTRY_ID'            => $post['COUNTRY_ID'],
            'DETECT_TYPE'           => $post['DETECT_TYPE'],
            'LAUNCH_TYPE'           => $post['LAUNCH_TYPE'],
            'TORPEDO_WEIGHT'        => $post['TORPEDO_WEIGHT'],
            'TORPEDO_DIAMETERS'     => $post['TORPEDO_DIAMETERS'],
            'TORPEDO_MAX_SPEED'     => $post['TORPEDO_MAX_SPEED'],
            'TORPEDO_MAX_RANGE'     => $post['TORPEDO_MAX_RANGE'],
            'TORPEDO_PROB_OF_HIT'   => $post['TORPEDO_PROB_OF_HIT']
        );

        $this->insert($data);
    }

    public function updateTorpedo($post, $id)
    {
        $data = array(
            'TORPEDO_NAME'          => $post['TORPEDO_NAME'],
            'COUNTRY_ID'            => $post['COUNTRY_ID'],
            'DETECT_TYPE'           => $post['DETECT_TYPE'],
            'LAUNCH_TYPE'           => $post['LAUNCH_TYPE'],
            'TORPEDO_WEIGHT'        => $post['TORPEDO_WEIGHT'],
            'TORPEDO_DIAMETERS'     => $post['TORPEDO_DIAMETERS'],
            'TORPEDO_MAX_SPEED'     => $post['TORPEDO_MAX_SPEED'],
            'TORPEDO_MAX_RANGE'     => $post['TORPEDO_MAX_RANGE'],
            'TORPEDO_PROB_OF_HIT'   => $post['TORPEDO_PROB_OF_HIT']
        );

        $this->update($data, 'master."M_TORPEDO"."TORPEDO_ID = "'.$id.'');
    }

    public function deleteTorpedo($id)
    {
        $this->delete('master."M_TORPEDO"."TORPEDO_ID" = '.$id.'');
    }

    public function selectTorpedo($id)
    {
        $query = $this->select()
            ->from('master.M_TORPEDO')
            ->where('master."M_TORPEDO"."TORPEDO_ID" = '.$id.'');
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