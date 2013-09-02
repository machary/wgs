<?php
class Cms_Model_Datatables_RadarType Extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Cms_Model_DbTable_RadarEccmType();

        $data = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['eccm_type_code'] = $dat['ECCM_TYPE_CODE'];

                $temp['Action'] = '<div class="custom-rows m-left5" style="width: 100px;">';
                $temp['Action'] .= '<a class="edit" href="gun/edit/id/'.$dat['ECCM_TYPE_ID'].'">Edit</a>';
                $temp['Action'] .= ' - <a class="delete" href="gun/delete/id/'.$dat['ECCM_TYPE_ID'].'">Delete</a>';
                $temp['Action'] .= '</div>';

                $totalAll = intval($dat['ECCM_TYPE_ID']);

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