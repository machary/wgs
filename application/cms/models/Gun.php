<?php
class Cms_Model_Gun Extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Cms_Model_DbTable_Gun();

        $data = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['gun_id']             = $dat['GUN_ID'];
                $temp['gun_name']           = $dat['GUN_NAME'];
                $temp['gun_caliber']        = $dat['GUN_CALIBER'];
                $temp['country_name']       = $dat['COUNTRY_NAME'];
                $temp['gun_category_name']  = $dat['GUN_CATEGORY_NAME'];

                $temp['Action'] = '<div class="custom-rows m-left5" style="width: 100px;">';
                $temp['Action'] .= '<a class="edit" href="gun/edit/id/'.$dat['GUN_ID'].'">Edit</a>';
                $temp['Action'] .= ' - <a class="delete" href="gun/delete/id/'.$dat['GUN_ID'].'">Delete</a>';
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