<?php
/**
 * Controller yang menyediakan action print data
 * @author irfan.muslim@sangkuriang.co.id
 */

class PrintController extends Zend_Controller_Action
{
    /**
     * Print telegram
     */
    public function printTelegramAction()
    {

        $this->_helper->_layout->setLayout('print-layout');

        $printMode = $this->_getParam('print-mode');

        $identity = Zend_Auth::getInstance()->getStorage()->read();

        $obj = new Peta_Model_TelegramCrud(null,$this->_request->getParam('id'));
        $arrobj = $obj->getforprint();

        // null or array
        $this->view->telegram   = $arrobj;


        switch($printMode){
            case '1': $this->view->isiBerita = $this->parsingBerita($arrobj['isi_berita'], 3100);
                      $this->getHelper('viewRenderer')->setViewScriptPathSpec('print/print-telegram1.phtml');
                      break;
            case '2': $this->view->isiBerita = $this->parsingBerita($arrobj['isi_berita'], 600);
                      $this->getHelper('viewRenderer')->setViewScriptPathSpec('print/print-telegram2.phtml');
                      break;
            case '3': $this->view->isiBerita = $this->parsingBerita($arrobj['isi_berita'], 800);
                      $this->getHelper('viewRenderer')->setViewScriptPathSpec('print/print-telegram3.phtml');
                      break;
            default : $isiBerita = $this->parsingBerita($arrobj['isi_berita'], 1200);
                      $this->view->isiBerita = $isiBerita;
                      $this->getHelper('viewRenderer')->setViewScriptPathSpec('print/print-telegram3.phtml');
        }



        $user = new Zend_Db_Table('user.logins');
        $userExist = $user->fetchRow($user->select()->where('id = ?', $identity->id));
        if(!empty($userExist))
        {
            $user->update(array('print_mode' => $printMode), $user->getAdapter()->quoteInto('id = ?', $identity->id));
        }

    }

    function substrws( $text, $len, $start )
    {
        $raw_text_length = strlen($text);

        //remaining text is less then max length
        if ($raw_text_length < $len + $start)
            $len = $raw_text_length;


        //check if white space before
        if ($start - $len >= 0 || $len == $raw_text_length) {

            $prev_whitespace_pos = $start;
            while( !preg_match('/\s/', substr($text, $prev_whitespace_pos, 1)) ) {
                $prev_whitespace_pos++;
            }
        } else
            $prev_whitespace_pos = 0;

        //last

        //check if white space after
        if ($len + $start > $raw_text_length)
            $next_whitespace_pos = $raw_text_length;
        else {
            $next_whitespace_pos = $len;
            while( !preg_match('/\s/', substr($text, $next_whitespace_pos, 1)) ) {
                $next_whitespace_pos++;
            }
        }


        if($next_whitespace_pos - $prev_whitespace_pos > 0){
            $text = substr($text, $prev_whitespace_pos, $next_whitespace_pos - $prev_whitespace_pos);
        } else {
            $text = substr($text, $prev_whitespace_pos, $next_whitespace_pos);
        }


        // close unclosed html tags
        if (preg_match_all("|<([a-zA-Z]+)>|",$text,$aBuffer))
        {
            if (!empty($aBuffer[1]))
            {
                preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);

                if (count($aBuffer[1]) != count($aBuffer2[1]))
                {
                    foreach ($aBuffer[1] as $index => $tag)
                    {
                        if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag)
                            $text .= '</'.$tag.'>';
                    }
                }
            }
        }
        return $text;
    }

    public function parsingBerita( $berita, $length) {
        $isiBerita = array();
        $beritaLength = strlen($berita);
        if($beritaLength > $length) {
            $max = floor( $beritaLength / $length) + 1;
            $i = 1;
            $startPoint = -1;
            while($beritaLength - $length > -$length) {
                array_push( $isiBerita, array( 'isi_berita' => $this->substrws( $berita, $length, ($startPoint + 1)), 'halaman' => $i, 'max' => $max));
                $beritaLength -= $length;
                $startPoint += $length;
                $i++;
            }
        } else {
            array_push( $isiBerita, array( 'isi_berita' =>  $berita, 'halaman' => 1, 'max' => 1));
        }
        return $isiBerita;
    }
}

