<?php
class Ops_Model_DbTable_Situasi extends Zend_Db_Table_Abstract
{
    protected $_name = 'situasi_poly';
    protected $_tableName = 'situasi_poly';
    protected $_primary = 'id';

    public function insertmatra($post, $idTaktis, $jumKekuatan, $matra, $user, $keterangan)
    {
        $list = new Cms_Model_DbTable_List();

        switch($matra){
            case 'Darat':
                $data = array(
                    'tanggal'           => $post['tanggal'],
                    'waktu'             => $post['waktu'],
                    'negara'            => $post['negara'],
                    'matra'             => $matra,
                    'keterangan'        => implode('|',$keterangan),
                    'id_simbol_taktis'  => $idTaktis,
                    'geom'              => $post['geom'],
                    'jumlah_kekuatan'   => $jumKekuatan,
                    'point'             => $post['longitude'].", ".$post['latitude'],
                    'id_login'          => $user['id'],
                    'id_skenario'       => $user['id_skenario'],
                    'id_team'           => $user['id_team']
                );
                break;
            case 'Laut' :
                $data = array(
                    'tanggal'           => $post['tanggal'],
                    'waktu'             => $post['waktu'],
                    'negara'            => $post['negara'],
                    'matra'             => $matra,
                    'keterangan'        => implode('|',$keterangan),
                    'id_simbol_taktis'  => $idTaktis,
                    'geom'              => $post['geom'],
                    'jumlah_kekuatan'   => $jumKekuatan,
                    'point'             => $post['longitude'].", ".$post['latitude'],
                    'id_login'          => $user['id'],
                    'id_skenario'       => $user['id_skenario'],
                    'id_team'           => $user['id_team']
                );
                break;
            case 'Udara' :
                $data = array(
                    'tanggal'           => $post['tanggal'],
                    'waktu'             => $post['waktu'],
                    'negara'            => $post['negara'],
                    'matra'             => $matra,
                    'keterangan'        => implode('|',$keterangan),
                    'id_simbol_taktis'  => $idTaktis,
                    'geom'              => $post['geom'],
                    'jumlah_kekuatan'   => $jumKekuatan,
                    'point'             => $post['longitude'].", ".$post['latitude'],
                    'id_login'          => $user['id'],
                    'id_skenario'       => $user['id_skenario'],
                    'id_team'           => $user['id_team']
                );
                break;
        }

        $this->insert($data);
    }

    public function insertpoint($post)
    {
        $table = new Zend_Db_Table('intelijen_point');
        $data = array(
            'geom'  =>  new Zend_Db_Expr("ST_GeomFromText('".$post['koordinat']."',4326)")
        );
        $table->insert($data);
    }

    public function selectpointid($post)
    {
        $table = new Zend_db_Table('intelijen_point');
        $query = $table->select()
                    ->from('intelijen_point')
                    ->order('id DESC')
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

    public function getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->from($this->_tableName)
        ;

        $string = '';
        switch($sortColumn)
        {
            case 1: $string = $this->_tableName.'.longitude';
            break;
            case 2: $string = $this->_tableName.'.latitude';
            break;
            case 3: $string = $this->_tableName.'.matra';
            break;
            case 4: $string = $this->_tableName.'.unsur';
            break;
            case 5: $string = $this->_tableName.'.keterangan';
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
                case 1 :
                    $query->where($this->_tableName.'."longitude" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 2 :
                    $query->where($this->_tableName.'."latitude" like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 3 :
                    $query->where($this->_tableName.'."matra" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 4 :
                    $query->where($this->_tableName.'."unsur" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 5 :
                    $query->where($this->_tableName.'."keterangan" like ' . "'" . '%'.$search.'%' . "'");
                    break;
            }
        }

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

    public function getalldatasituasi($idLogin, $limit, $offset, $sortColumn, $order, $filter, $search, $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('intel' => $this->_tableName))
            //->joinLeft(array('taktis' => 'master.simbol_taktis'), 'taktis."id" = intel."id_simbol_taktis"', array('taktis.nama'))
            ->where('id_login =?', $idLogin)
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'intel.tanggal';
            break;
            case 1: $string = 'intel.waktu';
            break;
            case 2: $string = 'intel.negara';
            break;
            case 3: $string = 'intel.matra';
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

        if(!empty($filter))
        {
            switch($filter)
            {
                case 0 :
                    $query->where('intel."matra" like '."'".$filter."'");
                    break;
                case 1 :
                    $query->where('intel."matra" like '."'".$filter."'");
                    break;
                case 2 :
                    $query->where('intel."matra" like '."'".$filter."'");
                    break;
            }
        }


        if(!empty($string)) {
            $query->order($string);
        }

        if($search != '' and $search != "undefined")
        {
            //$query->where('intel."tanggal" like ' . "'" . $search . "'");
            //$query->orWhere('intel."waktu" like ' . "'" . $search. "'");
            $query->orWhere('intel."negara" like ' . "'" . '%'.$search.'%' . "'");
            $query->orWhere('intel."matra" like ' . "'" . '%'.$search.'%' . "'");
        }



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

    public function updatecog($id, $status)
    {
        if($status == 'T')
        {
            $data = array(
                'keterangan' => 'show'
            );
        }
        else if($status = 'F')
        {
            $data = array(
                'keterangan' => ''
            );
        }

        $this->update($data, "id = '".$id."'");
    }

    public function updateKet($post, $id)
    {
        $data = array(
            'critical_requirement' => $post['critical_requirement'],
            'critical_capabilities' => $post['critical_capabilities'],
            'critical_vinerability' => $post['critical_vinerability']
        );

        $this->update($data, "id = '".$id."'");
    }

    public function getPolygon($matra, $user)
    {
        if(empty($user['id_skenario']))return false;
        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName, array('geom'))
           // ->join( "user.Team", 'user.Team.id = user.id_team', array())
            ->where('id_team = '.$user['id_team'])
            ->where('id_skenario = '.$user['id_skenario'])
           // ->where('user.Team.id = ?', $user['id_team'])
        ;

        if($matra == 'Udara' || $matra == 'Laut' || $matra == 'Darat')
        {
            $query->where("matra = '".$matra."'");
        }

        $result = $this->fetchAll($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    //@author : Erlan.Dwinov
    public function getLonlat( $matra = null )
    {
        $query = $this->select()->setIntegrityCheck(false)
                        ->from(array('poly' => $this->_tableName))
                        //->joinLeft(array('taktis' => 'master.simbol_taktis'), 'taktis."id" = poly."id_simbol_taktis"', array('taktis.filepath'))
        ;

        if( !empty($matra) ){
            if($matra == 'Udara' || $matra == 'Laut' || $matra == 'Darat')
            {
                $query->where('poly."matra" like ' . "'".'%'.$matra.'%'."'");
            }
        }

        $result = $this->fetchAll($query);

        if( empty($result) ) return null;

        return $result->toArray();
    }

	public function getSimbolTaktis($id)
	{
		$table = new Zend_Db_Table('master.simbol_taktis');

		$query = $table->select()->setIntegrityCheck(false)
						->from('master.simbol_taktis')
						->where('master."simbol_taktis"."id" = ?', $id)
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

    public function selectCOG($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->from($this->_tableName)
                      ->where("id = '".$id."'")
        ;

        $result = $this->fetchRow($query);

        if(!empty($query))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function getSkenAndLog($id)
    {
        if(empty($id))return false;
        $table = new Zend_Db_Table('user.logins');

        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('log' => 'user.logins'))
            ->joinleft(array('tim' => 'user.Team'), 'tim."id" = log."id_team"', array())
            ->joinleft(array('sken' => 'latihan.skenario'), 'sken."id" = tim."kode_skenario"', array('id_skenario' => 'sken.id'))
            ->where('log."id" = '.$id)
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

    public function getSituasi($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from(array('situasi' => $this->_tableName))
                    //->joinLeft(array('taktis'=>'master.simbol_taktis'), 'taktis."id"=intel."id_simbol_taktis"', array('taktis.filepath'))
                    ->where('situasi."id" = ?',$id)
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

    public function updateSituasi($post, $idTaktis, $jumKekuatan, $id, $matra, $keteranganArr)
    {
        $list = new Cms_Model_DbTable_List();

        switch($matra){
            case 'Darat':
                $data = array(
                    'tanggal'           => $post['tanggal'],
                    'waktu'             => $post['waktu'],
                    'negara'            => $post['negara'],
                    'matra'             => $matra,
                    'id_simbol_taktis'  => $idTaktis,
                    'geom'              => $post['geom'],
                    'jumlah_kekuatan'   => $jumKekuatan,
                    'point'             => $post['longitude'].', '.$post['latitude'],
	                'keterangan'        => implode('|', $keteranganArr)
                );
                break;
            case 'Laut' :
                $data = array(
                    'tanggal'           => $post['tanggal'],
                    'waktu'             => $post['waktu'],
                    'negara'            => $post['negara'],
                    'matra'             => $matra,
                    'id_simbol_taktis'  => $idTaktis,
                    'geom'              => $post['geom'],
                    'jumlah_kekuatan'   => $jumKekuatan,
                    'point'             => $post['longitude'].', '.$post['latitude'],
	                'keterangan'        => implode('|', $keteranganArr)
                );
                break;
            case 'Udara' :
                $data = array(
                    'tanggal'           => $post['tanggal'],
                    'waktu'             => $post['waktu'],
                    'negara'            => $post['negara'],
                    'matra'             => $matra,
                    'id_simbol_taktis'  => $idTaktis,
                    'geom'              => $post['geom'],
                    'jumlah_kekuatan'   => $jumKekuatan,
                    'point'             => $post['longitude'].', '.$post['latitude'],
	                'keterangan'        => implode('|', $keteranganArr)
                );
                break;
        }

        $this->update($data,"id = '".$id."'");
    }

    public function deletesituasi($id)
    {
        $this->delete("id = '".$id."'");
    }

    public function simbolPergerakan($id)
    {
        $table = new Zend_Db_Table('master.simbol_pergerakan');
        $table->_cols = array('id'=>'id', 'nama'=>'nama', 'filepath'=>'filepath');
        $table->_primary = 'id';

        $query = $table->select()->setIntegrityCheck(false)
                        ->from('master.simbol_pergerakan')
                        ->where('id = ?', $id);

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


    //Pergerakan
    public function tablePergerakan(){
        $table = new Zend_Db_Table('situasi_pergerakan');
        $table->_cols = array(
            'id'                    => 'id',
            'id_simbol_pergerakan'  => 'id_simbol_pergerakan',
            'point'                 => 'point',
            'rotation'              => 'rotation',
            'size'                  => 'size',
            'id_team'               => 'id_team',
        );
        $table->_primary = 'id';

        return $table;
    }

    public function saveSimbolPergerakan($idSimTak, $point, $rotate, $size, $idTeam)
    {
        $table = $this->tablePergerakan();
        $data = array(
            'id_simbol_pergerakan'  => $idSimTak,
            'point'                 => $point,
            'rotation'              => empty($rotate) ? 0 : $rotate,
            'size'                  => empty($size) ? 0 : $size,
            'id_team'               => $idTeam
        );

        $table->insert($data);
    }

    public function getPergerakan( $idTeam = null )
    {
        if( empty($idTeam) ) return false;

        $table = $this->tablePergerakan();
        $query = $table->select()->setIntegrityCheck(false)
                        ->from(array('situasi'=>'situasi_pergerakan'))
                        ->joinLeft(array('sim'=>'master.simbol_pergerakan'), 'sim."id"=situasi."id_simbol_pergerakan"', array('sim.filepath'))
                        ->where('situasi.id_team = ?', $idTeam)
        ;

        $result = $table->fetchAll($query);
        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function editpergerakan($id_team)
    {
        $table = new Zend_Db_Table('situasi_pergerakan');
        $table->_cols = array(
            'id'                    => 'id',
            'id_simbol_pergerakan'  => 'id_simbol_pergerakan',
            'point'                 => 'point',
            'rotation'              => 'rotation',
            'size'                  => 'size',
            'id_team'               => 'id_team'
        );
        $table->_primary = 'id';

        $query = $table->select()->setIntegrityCheck(false)
                        ->from(array('intel' => 'situasi_pergerakan'))
                        ->joinLeft(array('simbol' => 'master.simbol_pergerakan'), 'simbol."id"=intel."id_simbol_pergerakan"', array('simbol.filepath'))
                        ->where('id_team = ?', $id_team);

        $result = $table->fetchAll($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function updatepergerakan($idSimTak, $point, $rotate, $size, $id)
    {
        $table = new Zend_Db_Table('situasi_pergerakan');
        $table->_cols = array(
            'id'                    => 'id',
            'id_simbol_pergerakan'  => 'id_simbol_pergerakan',
            'point'                 => 'point',
            'rotation'              => 'rotation',
            'size'                  => 'size',
            'id_team'               => 'id_team'
        );
        $table->_primary = 'id';

        $data = array(
            'id_simbol_pergerakan'  => $idSimTak,
            'point'                 => $point,
            'rotation'              => $rotate,
            'size'                  => $size
        );

        $table->update($data, "id = '".$id."'");
    }

    public function deletepergerakan($id_team)
    {
        $table = new Zend_Db_Table('situasi_pergerakan');
        $table->_cols = array(
            'id'                    => 'id',
            'id_simbol_pergerakan'  => 'id_simbol_pergerakan',
            'point'                 => 'point',
            'rotation'              => 'rotation',
            'size'                  => 'size',
            'id_team'               => 'id_team'
        );
        $table->_primary = 'id';

        $table->delete("id_team = '".$id_team."'");
    }
}