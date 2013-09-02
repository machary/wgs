<?php
class Cms_Model_Datatables_Home extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '')
    {
        $tableNews = new Cms_Model_DbTable_Home();
        $list = new Cms_Model_DbTable_List();

        $data = $tableNews->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableNews->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['judul'] = $dat['judul'];
                //$temp['tanggal'] = date('d-m-Y', $dat['tanggal']);
                $temp['tanggal'] = $dat['tanggal'];
                $temp['Action'] = '<div class="custom-rows m-left5" style="width: 100px;">';
                $temp['Action'] .= '<a class="edit" href="edit/id/'.$dat['id'].'">Edit</a>';
                $temp['Action'] .= ' - <a onclick=return confirm("Anda yakin?") class="delete" href="delete/id/'.$dat['id'].'">Delete</a>';
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