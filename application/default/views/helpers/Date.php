<?php
/**
 * Helper Date
 */
class Zend_View_Helper_Date extends Zend_View_Helper_Abstract
{

    public function __construct() 
    {}

    public function date($date = null, $separator = '/', $toFormat = 'id')
    {
        if( empty($date) ) return false;
        switch( $toFormat ) {
            case 'id' :
                $date = self::ConvertDateString($date);
                return self::covertDateToId($date);
                break;
            case 'en' :
                return self::covertDateToEn($date, '-');
                break;
        }
    }
    
    // untuk konversi format tanggal indonesia ke format english yyyy-mm-dd
    protected function covertDateToEn($date, $separator = '/')
    {
        if( empty($date) ) return false;

        //$date berisi tanggal dlm format indo, e.x : 27 Agustus 2011
        // menghasilkan array [0] => tanggal, [1] => bulan (dlm format indo), [2]=> tahun
        $get_date = explode($separator, $date);

        $tanggal = $get_date[0];
        $bulan = $get_date[1];
        $tahun = $get_date[2];

        if( $separator == '/' ){
            return $tahun .'-'. $bulan .'-'. $tanggal;
        } else {
            switch(strtolower($bulan)){
                case "januari" : $month = '01'; break;
                case "februari" : $month = '02'; break;
                case "maret" : $month = '03'; break;
                case "april" : $month = '04'; break;
                case "mei" : $month = '05'; break;
                case "juni" : $month = '06'; break;
                case "juli" : $month = '07'; break;
                case "agustus" : $month = '08'; break;
                case "september" : $month = '09'; break;
                case "oktober" : $month = 10; break;
                case "november" : $month = 11; break;
                case "desember" : $month = 12; break;
            }
        }

        return $tahun .'-'. $month .'-'. $tanggal;
    }

    // untuk konversi format tanggal En yyyy-mm-dd ke format indonesia
    protected function covertDateToId($date, $separator = '/')
    {
        if( empty($date) ) return false;

        $get_date = explode( $separator , $date);

        $year = $get_date[2];
        $bulan = $get_date[1];
        $date = $get_date[0];

        switch($bulan){
            case 1 : $month = "Januari"; break;
            case 2 : $month = "Februari"; break;
            case 3 : $month = "Maret"; break;
            case 4 : $month = "April"; break;
            case 5 : $month = "Mei"; break;
            case 6 : $month = "Juni"; break;
            case 7 : $month = "Juli"; break;
            case 8 : $month = "Agustus"; break;
            case 9 : $month = "September"; break;
            case 10 : $month = "Oktober"; break;
            case 11 : $month = "November"; break;
            case 12 : $month = "Desember"; break;
        }
        return $date .' '. $month .' '. $year;
    }

    protected function ConvertDate($dateadd){
        list($d, $m, $y) = explode('/', $dateadd);
        $mk=mktime(0, 0, 0, $m, $d, $y);
        $dateadd_display=strftime('%Y-%m-%d',$mk);
        return $dateadd_display;
    }

    protected function ConvertDateString($dateadd){
        list($y, $m, $d) = explode('-', $dateadd);
        $mk=@mktime(0, 0, 0, $m, $d, $y);
        $dateadd_display=strftime('%d/%m/%Y',$mk);
        return $dateadd_display;
    }
}