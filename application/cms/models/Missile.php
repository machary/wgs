<?php
class Cms_Model_Missile extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Cms_Model_DbTable_Missile();

        $data = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {

                $temp['missile-name']                   = $dat['MISSILE_NAME'];
                $temp['country_name']                   = $dat['COUNTRY_NAME'];
                $temp['missile_length']                 = $dat['MISSILE_LENGTH'];
                $temp['missile_max_spd_knot']           = $dat['MISSILE_MAX_SPD_KNOT'];
                $temp['missile_min_range']              = $dat['MISSILE_MIN_RANGE'];
                $temp['missile_weight']                 = $dat['MISSILE_WEIGHT'];
                $temp['missile_prob_of_hit']            = $dat['MISSILE_PROB_OF_HIT'];
                $temp['missile_mid_course_type_name']   = $dat['MISSILE_MID_COURSE_TYPE_NAME'];

                $temp['Action'] = '<div class="custom-rows m-left5" style="width: 100px;">';
                $temp['Action'] .= '<a class="edit" href="missile/edit/id/'.$dat['MISSILE_ID'].'">Edit</a>';
                $temp['Action'] .= ' - <a class="delete" onclick="return confirm(\'Anda yakin?\')" href="missile/delete/id/'.$dat['MISSILE_ID'].'">Delete</a>';
                $temp['Action'] .= '</div>';

                $totalAll = intval($dat['MISSILE_ID']);

                array_push($jsonString, array_values($temp));
            }
        }

        $jsonArray = array();
        $jsonArray[ 'sEcho' ] = $sEcho;
        $jsonArray[ 'iTotalRecords' ] = $queryrowsCount;
        $jsonArray[ 'iTotalAllValue' ] = $totalAll;
        $jsonArray[ 'iTotalDisplayRecords' ] = $queryrowsCount;
        $jsonArray[ 'aaData' ] = $jsonString;

        return json_encode( $jsonArray );
    }
}