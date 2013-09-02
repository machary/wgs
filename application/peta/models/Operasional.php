<?php
class Peta_Model_Operasional Extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Peta_Model_DbTable_CbOperasional();

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
                $temp['no_cb_operasional']  = $dat['no_cb_operasional'];
                $temp['waktu_pembuatan']    = $dat['waktu_pembuatan'];

                $link_no_cb = str_replace('/','-',$dat['no_cb_operasional']);
                $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'decisive', 'no_cb_operasional'=>$link_no_cb)).'">Decisive Point</a> - <a href="'.$hUrl->url(array('action'=>'editcb', 'id'=>$dat['id'])).'">Ubah</a>';
				// link ke rute
				$temp['Action'] .= ' - <a href="'.$hUrl->url(array('controller'=>'rute.operasional','action'=>'index', 'cbid'=>$dat['id'])).'">Rute Laut</a>';
				// link rute udara
				$temp['Action'] .= ' - <a href="'.$hUrl->url(array('controller'=>'rute.operasional','action'=>'udara.index', 'cbid'=>$dat['id'])).'">Rute Udara</a>';

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

    public function datatablesJSONApidecisive($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view, $no_cb)
    {
        $tableGunCategory = new Peta_Model_DbTable_Operasional();

        $data = $tableGunCategory->getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count = false, $no_cb);

        $queryrowsCount = $tableGunCategory->getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count = true, $no_cb);

        $jsonString = array();

        $get_nocb = str_replace('/','-',$no_cb);
        $hUrl = new Zend_View_Helper_Url();
        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['no_cb_operasional']  = $dat['no_cb_operasional'];
                //$temp['sandi_operasi']    = $dat['sandi_operasi'];
                $temp['longitude']    = $dat['longitude'];
                $temp['latitude']    = $dat['latitude'];

                $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'deletedecisive', 'id'=>$dat['id_dec'], 'no_cb' => $get_nocb)).'">Hapus</a>';

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