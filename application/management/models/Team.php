<?php
class Management_Model_Team Extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Management_Model_DbTable_Team();

        $data = $tableGunCategory->getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableGunCategory->getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

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
                $temp['subjabatan'] = $dat['nomor'];

                $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'editteam', 'id'=>$dat['id'])).'">Ubah</a>'
                    .' - '
                    .'<a class="act-delete" href="'.$hUrl->url(array('action'=>'deleteteam', 'id'=>$dat['id'])).'">Hapus</a>';

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

    public function datatablesJSONApiadd($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Management_Model_DbTable_Team();

        $data = $tableGunCategory->getAllDataAdd($limit, $offset, $sortColumn, $order, $filter, $search, $count = false, $id_team = 0);

        $queryrowsCount = $tableGunCategory->getAllDataAdd($limit, $offset, $sortColumn, $order, $filter, $search, $count = true, $id_team = 0);

        $jsonString = array();

        $hUrl = new Zend_View_Helper_Url();
        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['check']        = '<input type="checkbox" class="ceklis" name="check[]" value="'.$dat['id_log'].'"/>';;
                $temp['nrp']        = $dat['nrp'];
                $temp['nama']       = $dat['nama'];
                $temp['jabatan']    = $dat['name'];

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

    public function datatablesJSONApiedit($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                         $filter = '', $search = '', $view, $idTeam)
    {
        $tableGunCategory = new Management_Model_DbTable_Team();

        $data = $tableGunCategory->getAllDataAdd($limit, $offset, $sortColumn, $order, $filter, $search, $count = false, $idTeam);

        $queryrowsCount = $tableGunCategory->getAllDataAdd($limit, $offset, $sortColumn, $order, $filter, $search, $count = true, $idTeam);

        $jsonString = array();

        $hUrl = new Zend_View_Helper_Url();
        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                if($dat['id_team'] != 0)
                {
                    $temp['check']        = '<input type="checkbox" class="ceklis" checked="checked" name="check[]" value="'.$dat['id_log'].'"/>';
                }
                else
                {
                    $temp['check']        = '<input type="checkbox" class="ceklis" name="check[]" value="'.$dat['id_log'].'"/>';;
                }
                $temp['username']   = $dat['username'];
                $temp['jabatan']    = $dat['name'];
                $temp['nama']       = $dat['nama'];

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