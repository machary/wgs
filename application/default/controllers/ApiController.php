<?php
/**
 * <Controller untuk menghandle permintaan2 data & tidak perlu autentikasi>
 * @author Tajhul
 */
 
class ApiController extends Zend_Controller_Action
{
    private $_useProxy;
    public function init()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
        $this->_useProxy = true;
    }

    public function indexAction()
    {}

    /*
   * grabData <fn untuk mengambil data dari rest api>
   * @param : (string) url
   * return (xml,json,html,txt)
   * note : callback sementara baru xml
   * */
    private function grabData( $url = null )
    {
        try{
            if( $this->_useProxy ) { //jika pake proxy
                $options = array(
                    'http' => array(
                        'proxy' => '10.1.1.2:8080',
                        'request_fulluri' => true,
                    ),
                );
                $context = stream_context_create( $options );
                $data = file_get_contents( $url, false, $context );
            } else {  //jika tidak pake proxy
                $data = file_get_contents( $url );
            }

            return $data;

        } catch( Exception $e ) {
            return false;
        }
    }

    public function ibuprovAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        //Informasi cuaca kota-kota di indonesia
        $xml = self::grabData( 'http://data.bmkg.go.id/cuaca_indo_1.xml' );
        if( !$xml ) return false;

        $con = new Interchanger();
        $data = $con->convertXmlToArray( $xml );

        $table = new Zend_Db_Table( 'ibuprov' );
        $query = $table->select()->setIntegrityCheck(false)
            ->from('ibuprov', array('nama', 'longitude'=>'ST_X(geom)', 'latitude'=>'ST_Y(geom)'))
        ;
        $result = $table->fetchAll($query)->toArray();

        $tamp = array();
        $combine = array();
        $combined = array();
        foreach( $data['Isi']['Row'] as $row )
        {
            foreach($result as $raw)
            {
                if(strtolower($row->Kota) == strtolower($raw['nama']))
                {
                    $tamp['kota'] = (string)$row->Kota;
                    $tamp['cuaca'] = (string)$row->Cuaca;
                    $tamp['suhu_min'] = (string)$row->SuhuMin;
                    $tamp['suhu_max'] = (string)$row->SuhuMax;
                    $tamp['kelembapan_min'] = (string)$row->KelembapanMin;
                    $tamp['kelembapan_max'] = (string)$row->KelembapanMax;
                    $tamp['longitude'] = $raw['longitude'];
                    $tamp['latitude'] = $raw['latitude'];
                    array_push($combine, $tamp);
                }
            }
        }

        echo json_encode($combine);
    }
}
