<?php
class Cms_Model_JarakPangkalan extends Zend_Db_Table_Abstract
{
	public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
	                                  $filter = '', $search = '', $view)
	{
		$tableGunCategory = new Cms_Model_DbTable_JarakPangkalan();

		$data = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

		$queryrowsCount = $tableGunCategory->getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

		$jsonString = array();

		$totalAll = 0;
		if($queryrowsCount > 0)
		{
			$temp = array();
			foreach($data as $dat)
			{
				$temp['asal_pangkalan']     = $dat['nama_asal_pangkalan'];
				$temp['tujuan_pangkalan']   = $dat['nama_tujuan_pangkalan'];
				$temp['jarak']              = $dat['jarak'];

				$temp['Action'] = '<div class="custom-rows m-left5" style="width: 100px;">';
				$temp['Action'] .= '<a class="edit" href="jarak.pangkalan/editjarakpelabuhan/id/'.$dat['id'].'">Edit</a>';
				$temp['Action'] .= ' - <a class="delete" onclick="return confirm(\'Anda yakin?\')" href="jarak.pangkalan/deletejarakpelabuhan/id/'.$dat['id'].'">Delete</a>';
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