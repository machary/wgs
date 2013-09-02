<?php
class Cms_Model_DbTable_JarakPangkalan extends Zend_Db_Table_Abstract
{
	protected $_name = 'master.jarak_pangkalan';
	protected $_tableName = 'master.jarak_pangkalan';
	protected $_primary = 'id';

	public function addJarak($post)
	{
		$data = array(
			'asal_pangkalan'    => $post['asal_pangkalan'],
			'tujuan_pangkalan'  => $post['tujuan_pangkalan'],
			'jarak'             => $post['jarak'],

		);
		$this->insert($data);
	}

	public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $filter = '', $search = '', $count)
	{
		$query = $this->select()->setIntegrityCheck(false)
			->from(array('jarak' => $this->_tableName))
			->joinLeft(array('pangkalan' => 'pelabuhan'), 'pangkalan."gid" = jarak."asal_pangkalan"', array('nama_asal_pangkalan'=>'pangkalan.nama'))
			->joinLeft(array('pangkalan2' => 'pelabuhan'), 'pangkalan2."gid" = jarak."tujuan_pangkalan"', array('nama_tujuan_pangkalan'=>'pangkalan2.nama'))
		;

		$string = '';
		switch($sortColumn)
		{
			case 0: $string = 'nama_asal_pangkalan';
			break;
			case 1: $string = 'nama_tujuan_pangkalan';
			break;
			case 2: $string = 'jarak.jarak';
			break;
			case 3: $string = 'jarak.mil_laut';
			break;
		}

		if((strtolower($order) == 'asc') || (strtolower($order) == 'undefined'))
		{
			$string .= ' ASC';
		}
		else
		{
			$string .= ' DESC';
		}

		if($filter != '' && $search != '')
		{
			switch($filter)
			{
				case 0 :
					$query->where('nama_asal_pangkalan like ' . "'" . '%'.$search.'%' . "'");
					break;
				case 1 :
					$query->where('nama_tujuan_pangkalan like ' ."'". '%'.$search.'%' . "'");
					break;
				case 2 :
					$query->where('jarak."jarak" like ' . "'" . '%'.$search.'%' . "'");
					break;
				case 3 :
					$query->where('jarak."mil_laut" like ' . "'" . '%'.$search.'%' . "'");
					break;
			}
		}

		$query->order($string);

		if( $count == false ) {

			$query->limit( $limit, $offset );
			$result = $this->fetchAll( $query );

			if( empty( $result ) ) return false;
			return $result->toArray();

		} else {

			$result = $this->fetchAll( $query );

			if( empty( $result ) ) return false;
			return count( $result );

		}

	}

	public function selectJarak($id)
	{
		$query = $this->select()
			->from($this->_tableName)
			->where('master."jarak_pangkalan"."id" = ?', $id);
		$result = $this->fetchRow($query);

		if(!empty($result))
		{
			return $result->toArray();
		}
		else
		{
			return null;
		}
	}

	public function updateJarak($post, $id)
	{
		$data = array(
			'asal_pangkalan'    => $post['asal_pangkalan'],
			'tujuan_pangkalan'  => $post['tujuan_pangkalan'],
			'jarak'             => $post['jarak']
		);

		$this->update($data, 'master."jarak_pangkalan"."id" = '.$id.'');
	}

	public function deleteJarak($id)
	{
		$this->delete('master."jarak_pangkalan"."id" = '.$id.'');
	}

	public function getJarak($asal, $tujuan)
	{
		$query = $this->select()->setIntegrityCheck(false)
					->from($this->_tableName)
					->where('master."jarak_pangkalan"."asal_pangkalan" = '."'".$asal."'")
					->where('master."jarak_pangkalan"."tujuan_pangkalan" = '."'".$tujuan."'")
		;

		$result = $this->fetchRow($query);

		if(!empty($result))
		{
			return $result->toArray();
		}
		else
		{
			$query = $this->select()->setIntegrityCheck(false)
				->from($this->_tableName)
				->where('master."jarak_pangkalan"."tujuan_pangkalan" = '."'".$asal."'")
				->where('master."jarak_pangkalan"."asal_pangkalan" = '."'".$tujuan."'")
			;

			$result = $this->fetchRow($query);

			if(!empty($result))
			{
				return $result->toArray();
			}
			else
			{
				return null;
			}
		}
	}
}