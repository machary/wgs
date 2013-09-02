<?php
/**
 * Halaman Integrasi Lain <integrasi dgn api dari website lain)
 * @author : Tajhul Faijin
 */
class IntegrasiController extends App_Controller
{
    private $_useProxy;
	public function init()
	{
		parent::init();
        $this->_useProxy = true; //isi true jika jaringan pake proxy
	}

    //hanya index yg nge-render viewer
    public function indexAction()
    {
        $default = 'cuacaID';
        $this->view->apiID = $this->_request->getParam('id', $default);
    }

    public function petacuacaAction()
    {

    }

    //menghandle request ajax untuk api (bmkg) dari viewer
    public function apiAction()
    {
        parent::disableViewAndLayout();

        //$apiID : kolerasi ke fn cuacaID, cuacaGlobal, prakGel di controller ini
        $apiID = $this->_request->getParam('id', null);

        $data = 'Tidak ada data'; //
        //jangan kosong
        if( !is_null($apiID) ){
            $data = self::$apiID();
        }

        //note : callback data html saja
        //karena data di bmkg kadang2 mengandung karakter yg tidak valid
        //ketika dipakai di format json / xml callback
        echo json_encode($data);
    }

    //untuk testing api
    public function testAction()
    {
        parent::disableViewAndLayout();
        echo self::earlyEarthquake();
    }

    /*
     * Informasi gempar terkini
     * @restApi: gempaterkini.xml
     * */
    private function earlyEarthquake()
    {
        $xml = self::grabData( 'http://data.bmkg.go.id/gempaterkini.xml' );
        if( !$xml ) return false;

        $con = new Interchanger();
        $data = $con->convertXmlToArray( $xml );

        $html = '<div class="api-data">';

        $html .= '<div class="grid_16 caption">';
        $html .= '<span> Informasi Gempa Terkini </span>';
        $html .= '<br />';
        $html .= '</div>';

        $html .= '<table id="tbl-api">';
        $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<td width="10%"> Tanggal </td>';
            $html .= '<td width="12%"> Jam </td>';
            $html .= '<td width="12%"> Koordinat </td>';
            $html .= '<td width="8%"> Lintang </td>';
            $html .= '<td width="8%"> Bujur </td>';
            $html .= '<td width="12%"> Magnitude </td>';
            $html .= '<td width="12%"> Kedalaman </td>';
            $html .= '<td width="26%"> Wilayah </td>';
        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody>';
        foreach( $data['gempa'] as $row ){
            $html .= '<tr>';
                $html .= '<td> '. $row->Tanggal .' </td>';
                $html .= '<td> '. $row->Jam .' </td>';
                $html .= '<td> '. $row->point->coordinates .' </td>';
                $html .= '<td> '. $row->Lintang .' </td>';
                $html .= '<td> '. $row->Bujur .' </td>';
                $html .= '<td> '. $row->Magnitude .' </td>';
                $html .= '<td> '. $row->Kedalaman .' </td>';
                $html .= '<td> '. $row->Wilayah .' </td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    /*
     * Informasi prakiraan gelombang
     * @restApi: prakgel.xml
     * */
    private function prakGel()
	{
        $xml = self::grabData( 'http://data.bmkg.go.id/prakgel.xml' );
        if( !$xml ) return false;

        $con = new Interchanger();
        $data = $con->convertXmlToArray( $xml );
        $html = '<div class="api-data">';

        $html .= '<div class="grid_16 caption">';
        $html .= '<span> Prakiraan Gelombang </span>';
        $html .= '<br />';
        $html .= '<small> Tanggal : '. $data['Tanggal']['Mulai'] .'</small>';
        $html .= '<small> &nbsp; s/d &nbsp;</small>';
        $html .= '<small> Tanggal : '. $data['Tanggal']['Sampai'] .'</small>';
        $html .= '</div>';

        $html .= '<table id="tbl-api" width="100%">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<td width="15%"> Tinggi </td>';
        $html .= '<td> Daerah </td>';
        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody>';
        foreach( $data['Isi']['Row'] as $row ){
            $html .= '<tr>';
            $html .= '<td> '. $row->Tinggi .' </td>';
            $clearText = str_replace('&nbsp;','',$row->Daerah);
            $html .= '<td> '. stripslashes(html_entity_decode( $clearText )) .' </td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    /*
     * Informasi cuaca kota-kota di indonesia
     * @restApi: cuaca_indo_1.xml
     * */
    private function cuacaID()
    {
        $xml = self::grabData( 'http://data.bmkg.go.id/cuaca_indo_1.xml' );
        if( !$xml ) return false;

        $con = new Interchanger();
        $data = $con->convertXmlToArray( $xml );
        $html = '<div class="api-data">';
        $html .= '<div class="grid_16 caption">';
        $html .= '<span> Cuaca kota-kota besar di indonesia masmek</span>';
        $html .= '<br />';
        $html .= '<small> Tanggal : '. $data['Tanggal']['Mulai'] .'</small>';
        $html .= '<small> &nbsp; s/d &nbsp;</small>';
        $html .= '<small> Tanggal : '. $data['Tanggal']['Sampai'] .'</small>';
        $html .= '</div>';

        $html .= '<table id="tbl-api" width="100%">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<td> Kota </td>';
        $html .= '<td> Cuaca </td>';
        $html .= '<td> Suhu MIN </td>';
        $html .= '<td> Suhu MAX </td>';
        $html .= '<td> Kelembapan MIN </td>';
        $html .= '<td> Kelembapan MAX </td>';
        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody>';
        foreach( $data['Isi']['Row'] as $row ){
            $html .= '<tr>';
            $html .= '<td> '. $row->Kota .' </td>';
            $html .= '<td> '. $row->Cuaca .' </td>';
            $html .= '<td> '. $row->SuhuMin .'&deg C </td>';
            $html .= '<td> '. $row->SuhuMax .'&deg C </td>';
            $html .= '<td> '. $row->KelembapanMin .' </td>';
            $html .= '<td> '. $row->KelembapanMax .' </td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    /*
     * Informasi cuaca kota-kota di dunia
     * @restApi: cuaca_dunia_1.xml
     * */
    private function cuacaGlobal()
    {
        $xml = self::grabData( 'http://data.bmkg.go.id/cuaca_dunia_1.xml' );
        if( !$xml ) return false;

        $con = new Interchanger();
        $data = $con->convertXmlToArray( $xml );
        $html = '<div class="api-data">';

        $html .= '<div class="grid_16 caption">';
        $html .= '<span> Cuaca kota-kota besar di dunia</span>';
        $html .= '<br />';
        $html .= '<small> Tanggal : '. $data['Tanggal'] .'</small>';
        $html .= '</div>';

        $html .= '<table id="tbl-api" width="100%">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<td> Kota </td>';
        $html .= '<td> Cuaca </td>';
        $html .= '<td> Suhu MIN </td>';
        $html .= '<td> Suhu MAX </td>';
        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody>';
        foreach( $data['Isi']['Row'] as $row ){
            $html .= '<tr>';
            $html .= '<td> '. $row->Kota .' </td>';
            $html .= '<td> '. $row->Cuaca .' </td>';
            $html .= '<td> '. $row->SuhuMin .'&deg C </td>';
            $html .= '<td> '. $row->SuhuMax .'&deg C </td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

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
}