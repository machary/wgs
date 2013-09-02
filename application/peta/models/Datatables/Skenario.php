<?php
class Peta_Model_Datatables_Skenario extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'Nomor',
            '',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('latihan.skenario');
        $query = $this->queryBuild($table);
        return $table->fetchAll($query)->count();
    }

    public function getTotalDisplayRecords()
    {
        $table = new Zend_Db_Table('latihan.skenario');
        $query = $this->queryBuild($table);
        $this->_search($query);

        return $table->fetchAll($query)->count();
    }

    public function retrieveData()
    {
        $table = new Zend_Db_Table('latihan.skenario');
        $query = $this->queryBuild($table);
        $this->_search($query);

        $raw = $table->fetchAll($query);
        $result = array();
        $hUrl = new Zend_View_Helper_Url();

        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $activeUser = $identity->id;
        foreach ($raw as $row) {
            $t = array_values($row->toArray());
            $id = array_shift($t);

            $link = '';

            foreach($this->_params['rute'] as $rute)
            {
                $link .= '<a href="'.$hUrl->url(array('action'=> $rute.'.index', 'controller' => 'musuh', 'skenario_id'=>$id)).'">Rute ' . $rute . '</a>';
                $link .= ' | ';
            }

            $t[] = substr($link,0,-3);;

            $result[] = $t;
        }

        return $result;
    }

    public function queryBuild( $table)
    {
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array( 'skenario' => 'latihan.skenario'), array( 'skenario.id','skenario.nomor'))
            ->where('closed <> ?', 1);

        return $query;
    }
}
