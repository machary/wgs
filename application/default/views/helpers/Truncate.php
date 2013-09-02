<?php
/**
 * Helper Truncate
 */
class Zend_View_Helper_Truncate extends Zend_View_Helper_Abstract
{

    public function __construct() 
    {}
    
    /**
     * Fungsi truncate
     *
     * @param string $fullcontent
     * @param integer $character batasan karakter yg mau di-truncate
     *
     * @return string hasil yang sudah di-truncate
     */
    public function truncate($fullcontent, $character = 85)
    {
        $maxlength = $character;

        // Bersihkan ul li
        $fullcontent = preg_replace("/<li>/", "", $fullcontent);
        $fullcontent = preg_replace("/<\/li>/", ", ", $fullcontent);
        $fullcontent = preg_replace("/<ul>/", " ", $fullcontent);
        $fullcontent = preg_replace("/, <\/ul>/", ". ", $fullcontent);

        // Bersihkan tag HTML yg lain
        $content = strip_tags($fullcontent);

        $length = strlen($content);

        if ($length > $maxlength) {
            if (($content[$maxlength] == " ") OR ($content[$maxlength] == ",")
                    OR ($content[$maxlength] == ".")
                    OR ($content[$maxlength] == ";")
                    OR ($content[$maxlength] == ":")) {

                $content = substr($content, 0, $maxlength);
                if ($maxlength != ($length - 1)) {
                    $content .= "";
                }
            }
            else {
                for ($i = $maxlength; $i > 0; $i--) {
                    if (($content[$i] == " ") OR ($content[$i] == ",")
                            OR ($content[$i] == ".")
                            OR ($content[$i] == ";")
                            OR ($content[$i] == ":")) {

                        $content = substr($content, 0, $i);
                        if ($i != ($length - 1)) {
                            $content .= "";
                        }
                        break;
                    }
                    }
            }
        }

        return $content;
    }
}