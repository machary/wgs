<?php
class Cms_Model_DbTable_Missile extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.M_MISSILE';
    protected $_tableName = 'master.M_MISSILE';
    protected $_primary = 'MISSILE_ID';

    public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $filter = '', $search = '', $count)
    {
        $query =
            $this->select()->setIntegrityCheck(false)
            ->distinct()
            ->from(array('misil' => $this->_tableName), array('misil.MISSILE_ID', 'misil.MISSILE_NAME', 'misil.MISSILE_LENGTH', 'misil.MISSILE_MAX_SPD_KNOT','misil.MISSILE_MIN_RANGE', 'misil.MISSILE_WEIGHT', 'misil.MISSILE_PROB_OF_HIT'))
            ->join(array('mid' => 'master.M_MISSILE_MID_COURSE_TYPE'), 'mid."MISSILE_MID_COURSE_TYPE" = misil."MISSILE_MID_COURSE_TYPE"', array('mid.MISSILE_MID_COURSE_TYPE_NAME'))
            ->join(array('negara' => 'master.M_COUNTRY'), 'negara."COUNTRY_ID" = misil."COUNTRY_ID"', array('negara.COUNTRY_NAME'))
        ;

        switch($sortColumn)
        {
            case 0: $string = 'misil.MISSILE_NAME';break;
            case 1: $string = 'misil.MISSILE_LENGTH';break;
            case 2: $string = 'misil.MISSILE_MAX_SPD_KNOT';break;
            case 3: $string = 'misil.MISSILE_MIN_RANGE';break;
            case 4: $string = 'misil.MISSILE_WEIGHT';break;
            case 5: $string = 'misil.MISSILE_PROB_OF_HIT';break;
            case 8: $string = 'mid.MISSILE_MID_COURSE_TYPE_NAME';break;
            case 9: $string = 'negara.COUNTRY_NAME';break;
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
                    $query->where('misil."MISSILE_NAME" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where('negara."COUNTRY_NAME" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 5 :
                    $query->where('misil."MISSILE_LENGTH" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 6 :
                    $query->where('misil."MISSILE_MAX_SPD_KNOT" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 7 :
                    $query->where('misil."MISSILE_MIN_RANGE" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 8 :
                    $query->where('misil."MISSILE_WEIGHT" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 9 :
                    $query->where('misil."MISSILE_PROB_OF_HIT" like ' . "'" . '%'.$search.'%' . "'");
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

    public function addMissile($post)
    {
        $data = array(
            'MISSILE_NAME'                  => $post['MISSILE_NAME'],
            'COUNTRY_ID'                    => $post['COUNTRY_ID'],
            'MISSILE_MID_COURSE_TYPE'       => $post['MISSILE_MID_COURSE_TYPE'],
            'MISSILE_LENGTH'                => $post['MISSILE_LENGTH'],
            'MISSILE_MAX_SPD_KNOT'          => $post['MISSILE_MAX_SPD_KNOT'],
            'MISSILE_MIN_RANGE'             => $post['MISSILE_MIN_RANGE'],
            'MISSILE_WEIGHT'                => $post['MISSILE_WEIGHT'],
            'MISSILE_PROB_OF_HIT'           => $post['MISSILE_PROB_OF_HIT']
        );

        $this->insert($data);
    }

    public function updateMissile($post, $id)
    {
        $data = array(
            'MISSILE_NAME'                  => $post['MISSILE_NAME'],
            'COUNTRY_ID'                    => $post['COUNTRY_ID'],
            'MISSILE_MID_COURSE_TYPE'       => $post['MISSILE_MID_COURSE_TYPE'],
            'MISSILE_LENGTH'                => $post['MISSILE_LENGTH'],
            'MISSILE_MAX_SPD_KNOT'          => $post['MISSILE_MAX_SPD_KNOT'],
            'MISSILE_MIN_RANGE'             => $post['MISSILE_MIN_RANGE'],
            'MISSILE_WEIGHT'                => $post['MISSILE_WEIGHT'],
            'MISSILE_PROB_OF_HIT'           => $post['MISSILE_PROB_OF_HIT']
        );

        $this->update($data, 'master."M_MISSILE"."MISSILE_ID" = '.$id.'');

    }

    public function deleteMissile($id)
    {
        $this->delete('master."M_MISSILE"."MISSILE_ID" = '.$id.'');
    }

    public function selectMissile($id)
    {
        $query = $this->select()
            ->from('master.M_MISSILE')
            ->where('master."M_MISSILE"."MISSILE_ID" = ?', $id);
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