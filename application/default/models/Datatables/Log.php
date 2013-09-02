<?php
class Default_Model_Datatables_Log extends Zend_Db_Table_Abstract
{
    public function datatablesJSONApi($areaLevel, $areaID, $sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '', $view, $id = '')
    {
        $model = new Peta_Model_DbTable_LogTelegram();
        $list = new Cms_Model_DbTable_List();

        $data = $model->datalog($limit, $offset, $sortColumn, $order, $filter, $search, $count = false, $id);

        $queryrowsCount = $model->datalog($limit, $offset, $sortColumn, $order, $filter, $search, $count = true, $id);

        $jsonString = array();

        $hUrl = new Zend_View_Helper_Url();
        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            foreach($data as $dat)
            {
                $kepada = '';
                if ( $dat['kepada_id'] != null || $dat['kepada_id'] != '' )
                {
                    $kepadaIdArr = explode(',', $dat['kepada_id']);
                    $kepadaIdArrCount = count($kepadaIdArr) - 1;

                    $kepadaNameArr = array();
                    for ( $x = 0 ; $x <= $kepadaIdArrCount ; $x++ )
                    {
                        if ( $kepadaIdArr[$x] != ',' )
                        {
                            $kepadaRole = $model->getRoleName( intval( $kepadaIdArr[$x] ) );
                            array_push( $kepadaNameArr, $kepadaRole['name'] );
                        }
                    }

                    $kepada = implode( ', ', $kepadaNameArr );
                }

                $tembusan = '';
                if ( $dat['tembusan_id'] != null || $dat['tembusan_id'] != '' )
                {
                    $tembusanIdArr = explode(',', $dat['tembusan_id']);
                    $tembusanIdArrCount = count($tembusanIdArr) - 1;

                    $tembusanNameArr = array();
                    for ( $y = 0 ; $y <= $tembusanIdArrCount ; $y++ )
                    {
                        if ( $tembusanIdArr[$y] != ',' )
                        {
                            $tembusanRole = $model->getRoleName( intval( $tembusanIdArr[$y] ) );
                            array_push( $tembusanNameArr, $tembusanRole['name'] );
                        }
                    }

                    $tembusan = implode( ', ', $tembusanNameArr );
                }
                
                $temp['datetime'] = $dat['datetime'];
                $temp['nomor_telegram'] = '<a href="' . $hUrl->url( array( 'module' => 'peta', 'controller' => 'telegram', 'action' => 'view', 'id' => $dat['idtelegram'] ) ) . '">' . $dat['nomor_telegram'] . '</a>';
                $temp['username'] = $dat['username'];
                $temp['kepada'] = $kepada;
                $temp['tembusan'] = $tembusan;
                $temp['jadwal'] = $dat['jadwal'];
                $temp['team_name'] = $dat['team_name'];

                $totalAll = intval($dat['idtelegram']);

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