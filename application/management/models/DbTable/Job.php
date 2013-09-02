<?php
class Management_Model_DbTable_Job extends Zend_Db_Table_Abstract
{
    protected $_name = 'user.Job';
    protected $_tableName = 'user.Job';
    protected $_primary = 'id';

    public function getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('job' => $this->_tableName))
            ->join(array('log' => 'user.logins'), 'log."id" = job."id_login"', array('log.nrp', 'log.nama'))
            ->join(array('jab' => 'master.M_JABATAN'), 'jab."id_jabatan" = job."id_jabatan"', array('jab.nama_jabatan'))
            ->join(array('sub' => 'master.M_SUBJABATAN'), 'sub."id_subjabatan" = job."id_subjabatan"', array('sub.nama_subjabatan'))
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0:
                $string = 'log.nrp';
                break;
            case 1:
                $string = 'log.nama';
                break;
            case 2:
                $string = 'jab.nama_jabatan';
                break;
            case 3:
                $string = 'sub.nama_subjabatan';
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
                    $query->where('log."nrp" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where('log."nama" like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 2 :
                    $query->where('jab."nama_jabatan" like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 3 :
                    $query->where('sub."nama_subjabatan" like ' ."'". '%'.$search.'%' . "'");
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

    public function addJob($post, $jab, $sub, $id)
    {
        $data = array(
            'id_login' => $post['id'],
            'id_skenario' => $id,
            'id_jabatan' => $jab,
            'id_subjabatan' => $sub
        );
        $this->insert($data);
    }

    public function selectnrp($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
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

    public function getkogas()
    {
        $table = new Zend_Db_Table('master.M_JABATAN');
        $query =  $table->select()->setIntegrityCheck(false)
                        ->from('master.M_JABATAN')
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

    public function getsubkogas()
    {
        $table = new Zend_Db_Table('master.M_SUBJABATAN');
        $query =  $table->select()->setIntegrityCheck(false)
            ->from('master.M_SUBJABATAN')
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

    public function getdatajob($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('job' => $this->_tableName))
            ->join(array('log' => 'user.logins'), 'log."id" = job."id_login"', array('log.nrp', 'log.nama'))
            ->where('job."id" = '.$id.'')
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

    public function updateJob($post, $id)
    {
        $data = array(
            'id_jabatan' => $post['id_jabatan'],
            'id_subjabatan' => $post['id_subjabatan']
        );

        $this->update($data, "id = '".$id."'");
    }
}