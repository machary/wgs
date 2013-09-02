<?php
/**
 * Datatables untuk Telegram
 * @author irfan.muslim@sangkuriang.co.id
 */
class Peta_Model_Datatables_Telegram extends App_Datatables
{

	protected $_outbox = false;
	protected $_draft = false;
    protected $_view = null;

	public  function __construct($params,$view = null)
	{
		if (isset($params['outbox'])){
			$this->_outbox = $params['outbox'];
		}

		if (isset($params['draft'])){
			$this->_draft = $params['draft'];
		}

        $this->_view = $view;

		parent::__construct($params);
	}


	public function getColumns() 
	{
        if ($this->_outbox){
            return array(
                'datetime',
                'nomor_telegram',
                'pengirim',
                'kepada',
                'tembusan',
                'rahasia',
                'segera',
                'isi_berita',
                'id',
            );
        }else{
            return array(
                'jadwal',
                'nomor_telegram',
                'pengirim',
                'kepada',
                'tembusan',
                'rahasia',
                'segera',
                'isi_berita',
                'id',
            );
        }
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('master.telegrams');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
		$identity = Zend_Auth::getInstance()->getStorage()->read();
		$table = new Zend_Db_Table('master.telegrams');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('master.telegrams'), array(
			'idtelegram', // id
		))
		;

		if ($this->_outbox){
			$query->where("pengirim_id = '".$identity->id."'");
		}else{
			$query->where("kepada_id LIKE '%,{$identity->id},%' OR tembusan_id LIKE '%,{$identity->id},%'");
            $query->where("jadwal <= '".date('Y-m-d H:i:s')."'");
		}

        $query->where("team_id = '".$identity->id_team."'");

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
			$str = '';
			foreach ($this->_searchable as $col => $isSearchable) {
				if ($isSearchable) {
					switch ($col) {
						default:
							$str .= '"'.$col.'" LIKE \'%'.$this->_search.'%\' OR';
							break;
					}
				}
			}
			$str = trim($str,' OR');
			$query->Where($str);
		}
	}


	public function retrieveData()
	{
		$identity = Zend_Auth::getInstance()->getStorage()->read();
        	$tablejoin = new Zend_Db_Table('user.login_telegram');
       	 $queryjoin = $tablejoin->select()->setIntegrityCheck(false)
            				   ->from('user.login_telegram')
            			 	   ->where('id_login = ?', $identity->id);

        $table = new Zend_Db_Table('master.telegrams');

		if ($this->_outbox){
            $query = $table->select()->setIntegrityCheck(false)
                ->from(array('master.telegrams'), array(
                    'idtelegram', // id
                    'datetime',
                    'nomor_telegram',
                    'pengirim',
//                   'kepada',
//                    'tembusan',
//                    'rahasia',
//                    'segera',
//                    'isi_berita',
                ))
                ->joinLeft(array('baca' => $queryjoin), 'baca."id_telegram" = master.telegrams."idtelegram"', array('id'))
                ->where("pengirim_id = '".$identity->id."'")
            ;
		}else{
            $query = $table->select()->setIntegrityCheck(false)
                ->from(array('master.telegrams'), array(
                    'idtelegram', // id
                    'jadwal',
                    'nomor_telegram',
                    'pengirim',
//                    'kepada',
//                    'tembusan',
//                    'rahasia',
//                    'segera',
//                    'isi_berita',
                ))
                ->joinLeft(array('baca' => $queryjoin), 'baca."id_telegram" = master.telegrams."idtelegram"', array('id'))
                ->where("kepada_id LIKE '%,{$identity->id},%' OR tembusan_id LIKE '%,{$identity->id},%'")
            ;
			$query->where("jadwal <= '".date('Y-m-d H:i:s')."'");
		}

		if ($this->_draft){
			$query->where('isdraft IS TRUE');
		}else{
			$query->where('isdraft IS FALSE');
		}

//		if ($identity->role_id != 9) {
//			$query->where("team_id = '".$identity->id_team."'");
//		}

          $query->order($this->_orderColumn.' '.$this->_orderDirection);
//        $query->order('datetime desc');
//        if ($this->_outbox){
//            $query->order('datetime desc');
//        }else{
//            $query->order('jadwal desc');
//        }
          $query->limit($this->_limit, $this->_offset);

		$this->_search($query);
        	//echo $query;exit;
		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) 
		{
			$t = array_values($row->toArray());
            		$id = array_shift($t);
//           		$id_login = array_shift($t);

           		 $t[1] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$id)).'">'.$t[1].'</a>';

//			$t[5] = ($t[5]) ? 'rahasia' : '';
//			$t[6] = ($t[6]) ? 'segera' : '';
//           		$t[7] = stripcslashes($this->_view->truncateStr(html_entity_decode($t[7], ENT_QUOTES),30));
//            	$t[3] = stripcslashes($this->_view->truncateStr($t[3],10));
//            	$t[4] = stripcslashes($this->_view->truncateStr($t[4],10));

            		if($this->_outbox || $this->_draft)
            		{
                		if($identity->role_id != 9)
                		{
                    			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a>';
                		}
                		else
                		{
                    			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
					<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
                		}
            		}
            		else
            		{
                		if($identity->role_id != 9)
                		{
                    			$t[] = '<a href="'.$hUrl->url(array('action'=>'replay', 'id'=>$id)).'">Balas</a> |
                			<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a>';
                		}
                		else
                		{
                    			$t[] = '<a href="'.$hUrl->url(array('action'=>'replay', 'id'=>$id)).'">Balas</a> |
                			<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
					<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
                		}
            		}

            		if ($this->_outbox)
	    		 {   $t[3] = '';     }
	     		else
	    		 {  $t[3] = ($t[3]) ? 'read' : 'unread';      }
			$result[] = $t;
		}
        
		return $result;
	}

	public function getListTo($arrval)
	{

		$identity = Zend_Auth::getInstance()->getStorage()->read();

		$table = new Zend_Db_Table('user.logins');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('users'=>'user.logins'), array(
			'id',
			'username',
		))
                ->join(array('rol' => 'user.roles'), 'rol."id" = users."role_id"',array('name'))
                ->join(array('tim' => 'user.Team'), 'tim."id" = users."id_team"',array())
                ->join(array('sek' => 'latihan.skenario'), 'sek."id" = tim."kode_skenario"',array())
		;
        $query->where('sek."closed" = ?',0);

        if($identity->role_id != '9')
        {
            $query->where('users."id_team" = ?',$identity->id_team);
        }

//        if($identity->role_id == '9')
//        {
//            $query = $table->select()->setIntegrityCheck(false)
//                    ->from('user.roles', array('id','name'))
//                    ->order('id ASC');
//        }
//        else
//        {
//            $query = $table->select()->setIntegrityCheck(false)
//                ->from(array('log' => 'user.logins'), array('log.id'))
//                ->join(array('role' => 'user.roles'), 'role."id" = log."role_id"', array('id_role'=>'role.id','role.name'))
//                ->where('log."is_active" = ?', 1);
//
//            if ($identity->id_team != 0){
//                $query->where('log."id_team" = ?',$identity->id_team);
//            }
//            $query->order('role.id ASC');
//        }

		$raw = $table->fetchAll($query);

//		$result[] = array('','','All','<input type="checkbox" class="allto"/>');
//		$result[] = array('68','','Wasdal','<input type="checkbox" value="68" class="checkto"/>');

//        foreach($raw as $row)
//        {
//            if($identity->role_id == 9)
//            {
//                if($row['id'] != 1 && $row['id'] != 3 && $row['id'] != 17)
//                {
//                    $result[] = array('',$row['id'],$row['name'],'<input type="checkbox" value="'.$row['id'].'" class="checkto" />');
//                }
//            }
//            else
//            {
//                $result[] = array('',$row['id'],$row['name'],'<input type="checkbox" value="'.$row['id_role'].'" class="checkto" />');
//            }
//            $t = array_values($row);
//            $t[] = '<input type="checkbox" value="'.$row['id_role'].'" class="checkto" />';
//            $result[] = $t;
//        }

        $result[] = array('','','All','<input type="checkbox" class="allto"/>');
        $result[] = array('68','','Wasdal','<input type="checkbox" value="68" class="checkto"/>');

		foreach ($raw as $row) {
			$t = array_values($row->toArray());

			if (stristr($arrval,','.$t[0].',')){
				$t[] = '<input type="checkbox" checked="checked" value="'.$t[0].'" class="checkto" />';
			}else{
				$t[] = '<input type="checkbox" value="'.$t[0].'" class="checkto" />';
			}
			$result[] = $t;

		}
		return $result;
	}
}