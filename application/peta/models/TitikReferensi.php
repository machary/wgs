<?php
class Peta_Model_TitikReferensi Extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view, $id_divisi)
    {
        $tableGunCategory = new Peta_Model_DbTable_TitikReferensi();

        $data = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false, $id_divisi);

        $queryrowsCount = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true, $id_divisi);

        $jsonString = array();

        $hUrl = new Zend_View_Helper_Url();
        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['longitude']      = $dat['longitude'];
                $temp['latitude']       = $dat['latitude'];
                $temp['keterangan']     = $dat['keterangan'];

                $temp['Action'] = '<div class="custom-rows m-left5" style="width: 100px;">';
                $temp['Action'] .= '<a class="edit" href="'.$hUrl->url(array('action'=>'edit', 'id'=>$dat['id'], 'id_divisi'=>$dat['id_divisi'])).'">Edit</a>';
                $temp['Action'] .= ' - <a class="delete" href="'.$hUrl->url(array('action'=>'delete', 'id'=>$dat['id'], 'id_divisi'=>$dat['id_divisi'])).'">Delete</a>';
                $temp['Action'] .= '</div>';

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