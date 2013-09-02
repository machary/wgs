<?php
class Management_Model_Job Extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Management_Model_DbTable_Job();

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
                $temp['nrp']        = $dat['nrp'];
                $temp['nama']       = $dat['nama'];
                $temp['jabatan']    = $dat['nama_jabatan'];
                $temp['subjabatan'] = $dat['nama_subjabatan'];

                $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'editjob', 'id'=>$dat['id'])).'">Ubah</a>'.' - '
                                    .'<a href="'.$hUrl->url(array('action'=>'deletejob', 'id'=>$dat['id'])).'">Hapus</a>';

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

    public function datatablesJSONApiadd($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view)
    {
        $tableGunCategory = new Management_Model_DbTable_Login();
        $model = new Management_Model_DbTable_job();
        $skenario = new Latihan_Model_Crud_Skenario();

        $tes = $skenario->getDataSkenario();

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
                $tamp = $model->selectnrp($dat['id']);

                if($tamp == null)
                {
                    $temp['nrp']        = $dat['nrp'];
                    $temp['nama']       = $dat['nama'];

                    $temp['jabatan'] = '<select name="jabatan[]" id="jabatan">'.
                        '<option value="">[PILIH]</option>';
                        foreach($model->getkogas() as $jab)
                        {
                            $temp['jabatan'] .= '<option value="'.$dat['id'].'-'.$jab['id'].'">'.$jab['nama_jabatan'].'</option>';
                        }
                        $temp['jabatan'] .= '</select>';

                    $temp['subjabatan'] = '<select name="subjabatan[]" id="subjabatan">'.
                        '<option value="">[PILIH]</option>';
                        foreach($model->getsubkogas() as $sub)
                        {
                            $temp['subjabatan'] .= '<option value="'.$sub['id'].'">'.$sub['nama_subjabatan'].'</option>';
                        }
                        $temp['subjabatan'] .= '</select>';

                    $temp['skenario'] = '<select name="skenario[]" id="skenario">'.
                                        '<option value ="">[PILIH]</option>';
                                        foreach($tes as $sken)
                                        {
                                            $temp['skenario'] .= '<option value="'.$sken['id'].'">'.$sken['nomor'].'</option>';
                                        }
                                        $temp['skenario'] .= '</select>';

                    $totalAll = intval($dat['id']);

                    array_push($jsonString, array_values($temp));
                }
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