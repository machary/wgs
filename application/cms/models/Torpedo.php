<?php
class Cms_Model_Torpedo Extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Cms_Model_DbTable_Torpedo();

        $data = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['torpedo_name']               = $dat['TORPEDO_NAME'];
                $temp['country_name']               = $dat['COUNTRY_NAME'];
                $temp['detect_type_name']           = $dat['DETECT_TYPE_NAME'];
                $temp['launch_type_name']           = $dat['LAUNCH_TYPE_NAME'];
                $temp['torpedo_weight']             = $dat['TORPEDO_WEIGHT'];
                $temp['terpedo_diameter']           = $dat['TORPEDO_DIAMETERS'];
                $temp['torpedo_max_speed']           = $dat['TORPEDO_MAX_SPEED'];
                $temp['torpedo_max_range']          = $dat['TORPEDO_MAX_RANGE'];
                $temp['torpedo_prob_of_hit']        = $dat['TORPEDO_PROB_OF_HIT'];

                $temp['Action'] = '<div class="custom-rows m-left5" style="width: 100px;">';
                $temp['Action'] .= '<a class="edit" href="torpedo/edit/id/'.$dat['TORPEDO_ID'].'">Edit</a>';
                $temp['Action'] .= ' - <a class="delete" onclick="return confirm(\'Anda yakin?\')" href="torpedo/delete/id/'.$dat['TORPEDO_ID'].'">Delete</a>';
                $temp['Action'] .= '</div>';

                $totalAll = intval($dat['GUN_ID']);

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