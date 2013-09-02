<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FebiFajar
 * Date: 7/4/12
 * Time: 5:22 PM
 */
 

class Management_Model_Pelaku Extends Zend_Db_Table_Abstract
{

    public function datatablesTeamJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableTeam = new Management_Model_DbTable_Team();

        $data = $tableTeam->getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableTeam->getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $hUrl = new Zend_View_Helper_Url();
        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
    //                $temp['nama']        = $dat['nama'];
    //                $temp['name']       = $dat['name'];
                $temp['jabatan']    = $dat['team_name'];

                $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'list', 'teamid'=>$dat['id'])).'">Lihat</a>';

                $totalAll = intval(@$dat['id']);

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