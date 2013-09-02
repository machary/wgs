<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_Kesatuan extends Zend_Db_Table_Abstract
{
    protected $_name = 'yonif';
    protected $_tableName = 'yonif';
    protected $_primary = 'gid';

    public function getYonif($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from($this->_tableName, array('gid', 'nama', 'lokasi', 'markas', 'geom',
                            'tipe', 'matra', 'id_kesatuan',
                            'longitude'=>'ST_X(geom)', 'latitude'=>'ST_Y(geom)',
                    ))
                    ->where("gid = '".$id."'");

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

    public function updateYonif($post, $id)
    {
        $data = array(
            'nama'          => $post['nama'],
            'lokasi'        => $post['lokasi'],
            'markas'        => $post['markas'],
            'geom'          => new Zend_Db_Expr("st_geomfromtext('".$post['geom']."', 4326)"),
            'tipe'          => $post['tipe'],
            'matra'         => $post['matra'],
            'id_kesatuan'   => $post['id_kesatuan']
        );

        $this->update($data, "gid = '".$id."'");
    }

    public function insertYonif($post)
    {
        $data = array(
            'nama'          => $post['nama'],
            'lokasi'        => $post['lokasi'],
            'markas'        => $post['markas'],
            'geom'          => new Zend_Db_Expr("st_geomfromtext('".$post['geom']."', 4326)"),
            'tipe'          => $post['tipe'],
            'matra'         => $post['matra'],
            'id_kesatuan'   => $post['id_kesatuan']
        );

        $this->insert($data);
    }

    public function deleteYonif($id)
    {
        $this->delete("gid = '".$id."'");
    }
}