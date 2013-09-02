<?php
class Cms_Model_DbTable_ProductStaff extends Zend_Db_Table
{
    protected $_name = 'user.logins';
    protected $_tableName = 'user.logins';
    protected $_primary = 'id';

    public function getIdTeam($id)
    {
        $query = $this->select()
                    ->from($this->_tableName, array('id_team'))
                    ->where("id = '".$id."'")
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

    public function getTeamId($id_team)
    {
        $query = $this->select()
            ->from($this->_tableName, array('id'))
            ->where("id_team = '".$id_team."'")
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

    public function lock($id)
    {
        $data = array(
            'lock'  => 1
        );

        $this->update($data, "id = '".$id."'");
    }

    public function unlock($id)
    {
        $data = array(
            'lock'  => 0
        );

        $this->update($data, "id = '".$id."'");
    }

    public function tipeskenario($id_team)
    {
        $query = $this->select()->setIntegrityCheck(false)
                        ->from( array( 'tim' => 'user.Team' ), array() )
                        ->joinLeft( array( 'sken' => 'latihan.skenario' ), 'sken."id" = tim."kode_skenario"')
                        ->where( 'tim."id" = ?', $id_team )
                        ->limit(1)
        ;

        $result = $this->fetchAll($query);

        return (!empty($result)) ? $result->toArray() : null;
    }

    public function selectLock($id)
    {
        $query = $this->select()
                    ->from($this->_tableName, array('lock'))
                    ->where("id = '".$id."'")
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

    public function addMProdStaff($prodStaff)
    {
        $table = new Zend_Db_Table('master.M_PRODUCT_STAFF');

        $data = array(
            'nama_product_staff' => $prodStaff,
            'id_jabatan' => null,
            'id_langkah' => null
        );

        return $table->insert($data);
    }

    public function getMProdStaff()
    {
        $table = new Zend_Db_Table('M_PRODUCT_STAFF');

        $query = $table->select()->setIntegrityCheck(false)
                        ->from('master.M_PRODUCT_STAFF')
                        ->order('id DESC')
                        ->limit(1)
        ;

        $result = $table->fetchRow($query)->toArray();
        return (!empty($result)) ? $result : null;
    }

    public function getMProdStaffName($name)
    {
        $table = new Zend_Db_Table('M_PRODUCT_STAFF');

        $query = $table->select()->setIntegrityCheck(false)
                        ->from('master.M_PRODUCT_STAFF')
                        ->order('id DESC')
                        ->where('lower(nama_product_staff) = ?', strtolower($name))
        ;

        $result = $table->fetchRow($query);
        return (!empty($result)) ? $result->toArray() : null;
    }
}