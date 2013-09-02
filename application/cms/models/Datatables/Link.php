<?php
class Cms_Model_Datatables_Link extends Zend_Db_Table_Abstract
{
    private $_baseUrl = '';
    public function __construct(){
        $this->_baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
    }

    public function datatablesJSONApi($sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $url)
    {
        $model = new Cms_Model_DbTable_Link();

        $countData = $model->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);
        $jsonString = array();
        if($countData > 0)
        {
            $rows = $model->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);
            $temp = array();
            foreach($rows as $row)
            {
                $temp['judul'] = $row['judul'];
                $temp['tautan'] = $row['tautan'];

                $file = empty( $row['file'] ) ? 'default.png' : $row['file'];
                $temp['file'] = '<img src="'. $url . $file .'" alt="">';

                $temp['Action'] = '<div>';
                $temp['Action'] .= '<a class="edit" href="'. $this->_baseUrl .'/cms/link/edit/id/'. $row['id'] .'">Edit</a>';

                $file = empty( $row['file'] ) ? 0 : $row['file'];
                $temp['Action'] .= ' | <a onclick="return confirm(\'Anda yakin ?\')" href="'. $this->_baseUrl .'/cms/link/delete/id/'. $row['id'] .'/file/'. $file .'">Delete</a>';

                $temp['Action'] .= '</div>';

                array_push($jsonString, array_values($temp));
            }
        }

        $jsonArray = array();
        $jsonArray[ 'sEcho' ] = $sEcho;
        $jsonArray[ 'iTotalRecords' ] = $countData;
        $jsonArray[ 'iTotalAllValue' ] = $model->countAll();
        $jsonArray[ 'iTotalDisplayRecords' ] = $countData;
        $jsonArray[ 'aaData' ] = $jsonString;

        return json_encode( $jsonArray );
    }


}