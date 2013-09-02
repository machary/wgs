<?php



class Zend_View_Helper_TruncateStr extends Zend_View_Helper_Abstract
{
    /**
     * Fungsi truncate
     *
     * @param string $fullcontent
     * @param integer $character batasan karakter yg mau di-truncate
     *
     * @return string hasil yang sudah di-truncate
     *
     * @author irfan.muslim@sangkuriang.co.id
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
                    $content .= "...";
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
                                $content .= "...";
                            }
                            break;
                        }
                }
            }

        }
        return $content;
    }

    //function tambahan
    public function truncateStr($fullcontent, $character = 85)
    {
        $contentStr = $this->truncate($fullcontent, $character);

        $length2 = strlen($contentStr);
        if ($length2 > $character+3) {
            $contentStr = substr($contentStr, 0, $character);
            $contentStr .= "...";
        }

        return $contentStr;

    }
}
