<?php
class Cms_Model_GunCategory extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Cms_Model_DbTable_GunCategory();

        $data = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search);

        $queryrowsCount = count($data);

        $jsonString = array();

        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['gun_category']       = $dat['GUN_CATEGORY'];
                $temp['gun_category_name']  = $dat['GUN_CATEGORY_NAME'];

                array_push($jsonString, array_values($temp));
            }
        }

        $jsonArray = array();
        $jsonArray[ 'sEcho' ] = $sEcho;
        $jsonArray[ 'iTotalRecords' ] = $queryrowsCount;
        $jsonArray[ 'iTotalDisplayRecords' ] = $queryrowsCount;
        $jsonArray[ 'aaData' ] = $jsonString;

        return json_encode( $jsonArray );
    }
}