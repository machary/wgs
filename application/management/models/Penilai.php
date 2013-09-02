<?php
class Management_Model_Penilai extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Management_Model_DbTable_Penilai();

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
                $temp['nama']        = $dat['nama'];
                $temp['team_name']    = $dat['team_name'];
                $temp['nama_jabatan']       = $dat['nama_jabatan'];

                $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$dat['id_penilai'])).'">Ubah</a>'.' - '
                    .'<a href="'.$hUrl->url(array('action'=>'delete', 'id'=>$dat['id_penilai'])).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';

                $totalAll = intval($dat['id_login']);

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