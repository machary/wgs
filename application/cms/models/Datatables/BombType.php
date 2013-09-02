<?php
class Cms_Model_Datatables_BombType Extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Cms_Model_DbTable_BombType();

        $data = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['bomb_type_name'] = $dat['BOMB_TYPE_NAME'];

                $temp['Action'] = '<div class="custom-rows m-left5" style="width: 100px;">';
                $temp['Action'] .= '<a class="edit" href="gun/edit/id/'.$dat['BOMB_TYPE'].'">Edit</a>';
                $temp['Action'] .= ' - <a class="delete" href="gun/delete/id/'.$dat['BOMB_TYPE'].'">Delete</a>';
                $temp['Action'] .= '</div>';

                $totalAll = intval($dat['BOMB_TYPE']);

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