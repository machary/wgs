<?php
class Ops_Model_Situasi Extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Ops_Model_DbTable_Situasi();

        $data = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $hUrl = new Zend_View_Helper_Url();
        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                if($dat['kode_cog'] != null || $dat['kode_cog'] != '')
                {
                    $temp['check']          = '<input type="checkbox" class="ceklis" name="check[]" checked="checked" value="'.$dat['id'].'"/>';
                }
                else
                {
                    $temp['check']          = '<input type="checkbox" class="ceklis" name="check[]" value="'.$dat['id'].'"/>';
                }
                $temp['longitude']      = $dat['longitude'];
                $temp['latitude']       = $dat['latitude'];
                $temp['matra']          = $dat['matra'];
                $temp['unsur']          = $dat['unsur'];
                $temp['keterangan']     = '<a href="'.$hUrl->url(array('action'=>'editinformasi', 'id'=>$dat['id'])).'">Ubah</a>';

                $totalAll = intval($dat['id']);

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

    public function datatableseditJSONApi($idLogin, $areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Ops_Model_DbTable_Situasi();

        $data = $tableGunCategory->getalldatasituasi($idLogin, $limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableGunCategory->getalldatasituasi($idLogin, $limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $hUrl = new Zend_View_Helper_Url();
        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['tanggal']    = $dat['tanggal'];
                $temp['waktu']      = $dat['waktu'];
                $temp['negara']     = $dat['negara'];
                $temp['matra']      = $dat['matra'];
                $temp['action']     = '<a href="'.$hUrl->url(array('action'=>'editsituasi', 'id'=>$dat['id'], 'matra'=>$dat['matra'])).'">Ubah - </a>';
                $temp['action']     .= '<a onclick="return confirm(\'Anda yakin ?\')" href="'.$hUrl->url(array('action'=>'deletesituasi', 'id'=>$dat['id'])).'">Hapus</a>';

                $totalAll = intval($dat['id']);

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