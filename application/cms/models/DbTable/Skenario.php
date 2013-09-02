<?php
class Cms_Model_DbTable_Skenario extends Zend_Db_Table
{
    public function download($id, $buku)
    {
        $table = new Zend_Db_Table('latihan.skenario');
        $query = $table->select()
                        ->from('latihan.skenario', array($buku))
                        ->where("id = '".$id."'")
        ;

        $result = $table->fetchRow($query);
        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function deletebuku($id, $field)
    {
        $table = new Zend_Db_Table('latihan.skenario');
        $data = array(
            $field => null
        );

        $table->update($data, "id = '".$id."'");
    }
}