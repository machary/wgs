<?php
/**
 * Datatables untuk Skenario
 * @author Febi
 */

class Ops_Model_Sendiri_Skenario extends App_Datatables
{
	public function getColumns()
	{
		return array(
			'nomor',
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

    /**
     * Di-override karena postgresql tidak bisa menggunakan column alias di
     * where clause
     */
    protected function _search($query)
    {
        if ($this->_search) {
            foreach ($this->_searchable as $col => $isSearchable) {
                if ($isSearchable) {
                    switch ($col) {
                        case 'tanggal':
                            $query->orWhere('skenario."tanggal" LIKE ?', "%{$this->_search}%");
                            break;
                        case 'nomor':
                            $query->orWhere('skenario."nomor" LIKE ?', "%{$this->_search}%");
                            break;
                        case 'buku1':
                            $query->orWhere('skenario."buku1" LIKE ?', "%{$this->_search}%");
                            break;
                        case 'buku2':
                            $query->orWhere('skenario."buku2" LIKE ?', "%{$this->_search}%");
                            break;
                        case 'closed':
                            $query->orWhere('skenario."closed" LIKE ?', "%{$this->_search}%");
                            break;
                        case 'prosrenmil_id':
                            $query->orWhere('skenario."prosrenmil_id" LIKE ?', "%{$this->_search}%");
                            break;
                        default:
                            $query->orWhere('"'.$col.'" LIKE ?', "%{$this->_search}%");
                            break;
                    }
                }
            }
        }
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
                $link .= '<a href="'.$hUrl->url(array('action'=> $rute.'.index', 'controller' => 'sendiri', 'skenario_id'=>$id)).'">Rute ' . $rute . '</a>';
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
