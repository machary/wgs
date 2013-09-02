<?php
class Management_Model_DbTable_Penilai extends Zend_Db_Table_Abstract
{
    protected $_name = 'user.penilai';
    protected $_tableName = 'user.penilai';
    protected $_primary = 'id_penilai';

    public function getjabatan()
    {
        $jab = new Zend_Db_Table('master.M_JABATAN');
        $query = $jab->select()
            ->from('master.M_JABATAN')
        ;

        $result = $jab->fetchAll($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('penilai' => $this->_tableName))
            ->join(array('log' => 'user.logins'), 'log."id" = penilai."id_login"', array('log.nama'))
            ->join(array('jab' => 'master.M_JABATAN'), 'jab."id_jabatan" = penilai."id_jabatan"', array('jab.nama_jabatan'))
            ->join(array('tim' => 'user.Team'), 'tim."id" = penilai."id_team"', array('tim.team_name'))
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0:
                $string = 'log.nama';
                break;
            case 1:
                $string = 'jab.nama_jabatan';
                break;
            case 2:
                $string = 'tim.team_name';
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

        if( $search != '')
        {
            $search = strtolower($search);

            $query->where('lower(log."nama") like ' . "'" . '%'.$search.'%' . "'");
            $query->orwhere('lower(jab."nama_jabatan") like ' ."'". '%'.$search.'%' . "'");
            $query->orwhere('lower(tim."team_name") like ' ."'". '%'.$search.'%' . "'");

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

    public function selectpenilai($id)
    {
        $query = $this->select()
            ->from($this->_tableName)
            ->where("id_penilai = '".$id."'")
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

    public function addpenilai($post)
    {
        $data = array(
            'id_login'  => $post['id_login'],
            'id_team'   => $post['id_team'],
            'id_jabatan'=> $post['id_jabatan']
        );
        $this->insert($data);
    }

    public function updatepenulis($post, $id)
    {
        $data = array(
            'id_login'  => $post['id_login'],
            'id_team'   => $post['id_team'],
            'id_jabatan'=> $post['id_jabatan']
        );
        $this->update($data, "id_penilai = '".$id."'");
    }

    public function deletepenilai($id)
    {
        $this->delete("id_penilai = '".$id."'");
    }

    public function selectpenilaiwithlogin($id)
    {
        $query = $this->select()
            ->from($this->_tableName)
            ->where("id_login = '".$id."'")
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

    public function selectAllwithlogin($id)
    {
        $query = $this->select()
            ->setIntegrityCheck(false)
            ->from(array( 'penilai' => $this->_tableName))
            ->join(array('jab' => 'master.M_JABATAN'), 'jab.id_jabatan = penilai.id_jabatan', 'nama_jabatan')
            ->where("id_login = '".$id."'")
        ;

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

    public function selectAllwithloginteam($id, $team)
    {
        $query = $this->select()
            ->setIntegrityCheck(false)
            ->from(array( 'penilai' => $this->_tableName))
            ->join(array('jab' => 'master.M_JABATAN'), 'jab.id_jabatan = penilai.id_jabatan', 'nama_jabatan')
            ->where("id_login = '".$id."'")
            ->where("id_team = '".$team."'")
        ;

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

    public function selectAllwithloginteamnama($id, $team, $nama)
    {
        $query = $this->select()
            ->setIntegrityCheck(false)
            ->from(array( 'penilai' => $this->_tableName))
            ->join(array('jab' => 'master.M_JABATAN'), 'jab.id_jabatan = penilai.id_jabatan', 'nama_jabatan')
            ->where("id_login = '".$id."'")
            ->where("id_team = '".$team."'")
            ->where("lower(jab.nama_jabatan) = '".$nama."'")
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

    public function selectAllTeamwithlogin($id)
    {
        $query = $this->select()
            ->setIntegrityCheck(false)
            ->from(array( 'penilai' => $this->_tableName))
            ->join(array('team' => 'user.Team'), 'penilai.id_team = team.id', 'team_name')
            ->where("id_login = '".$id."'")
        ;

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

    public function selectTeamwithlogin($id)
    {
        $query = $this->select()
            ->setIntegrityCheck(false)
            ->from(array( 'logins' => 'user.logins'))
            ->join(array( 'penilai' => $this->_tableName), 'penilai.id_login = logins.id')
            ->join(array('team' => 'user.Team'), 'penilai.id_team = team.id', 'team_name')
            ->where("id_login = '".$id."'")
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

    public function getScenario($id, $kogas)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array( 'penilai' => $this->_tableName))
            ->joinLeft(array('team' => 'user.Team'), 'penilai.id_team = team.id', 'team_name', null)
            ->joinLeft(array('ske' => 'latihan.skenario'), 'ske."id" = team."kode_skenario"')
            ->join(array('jab' => 'master.M_JABATAN'), 'jab.id_jabatan = penilai.id_jabatan')
            ->where('id_login =?', $id)
            ->where("nama_jabatan like '{$kogas}%'")
        ;
        return $this->fetchRow($query);

    }

    public function getScenarioWithIdKogas($id, $kogasID)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array( 'penilai' => $this->_tableName))
            ->joinLeft(array('team' => 'user.Team'), 'penilai.id_team = team.id', 'team_name', null)
            ->joinLeft(array('ske' => 'latihan.skenario'), 'ske."id" = team."kode_skenario"')
            ->join(array('jab' => 'master.M_JABATAN'), 'jab.id_jabatan = penilai.id_jabatan')
            ->where('id_login = ?', $id)
            ->where('jab.id_jabatan = ?', $kogasID)
        ;
        return $this->fetchRow($query);

    }

    public function selectAllJabTeamwithlogin($id)
    {
        $query = $this->select()
            ->setIntegrityCheck(false)
            ->from(array( 'penilai' => $this->_tableName))
            ->join(array('jab' => 'master.M_JABATAN'), 'jab.id_jabatan = penilai.id_jabatan', 'nama_jabatan')
            ->join(array('team' => 'user.Team'), 'penilai.id_team = team.id', 'team_name')
            ->where("id_login = '".$id."'")
        ;

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
}