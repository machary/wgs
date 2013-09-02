<?php
class Default_Model_Datatables_Artikel extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableNews = new Cms_Model_DbTable_Home();
        $list = new Cms_Model_DbTable_List();

        $data = $tableNews->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableNews->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $totalAll = $tableNews->countAll();
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $temp['judul'] = $dat['judul'];
                //$temp['tanggal'] = $list->ConvertDateString($dat['tanggal']);
                $temp['tanggal'] = $dat['tanggal'];

                $temp['Action'] = '<div class="custom-rows m-left5" style="width: 100px;">';
                $temp['Action'] .= '<a class="edit" href="'. $view->siteUrl('artikel/detail/id/' . $dat['id'] ) .'">Selengkapnya >></a>';
                $temp['Action'] .= '</div>';

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