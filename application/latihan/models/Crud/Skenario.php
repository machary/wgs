<?php
/**
 * Model untuk table Skenario Latihan
 * 
 * @author Kanwil
 */

/**
 * id Untuk Jenis Skenario Latihan
 *
 * 1 Bilkepkamla
 * 2 Latsisrenstrahan
 * 3 OYU AT
 * 4 PRA PKB Opsgab
 * 5 PKB Opsgab
 */
 
class Latihan_Model_Crud_Skenario extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'latihan';
	protected $_tableName = 'skenario';
	protected $_ignoreCols = array('buku1', 'buku2', 'closed');
	protected $_customElements = array(
		'tanggal' => array(
			'text',
			'label' => 'Waktu Pembuatan',
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('Date', false, array('format'=>'yyyy-MM-dd')),
			),
			// html
			'class' => 'input-date',
		),

        'id_skenario_latihan' => array(
            'select',
            'label' => 'Jenis Skenario Latihan',
            'required' => true,
            'multiOptions' => array(
                0 => '[Pilih]',
                1 => 'Bilkepkamla',
                2 => 'Latsisrenstrahan',
                3 => 'OYU AT',
                4 => 'PRA PKB Opsgab',
                5 => 'PKB Opsgab',
            ),
        ),
	);

	protected $_foreignKeys = array(
		'prosrenmil_id' => array( // nama kolom yg merupakan foreign key
			'label' => 'Pilih Prosrenmil',
			'allowEmpty' => false,
			'schema' => 'master',
			'table' => 'M_PROSRENMIL', // nama table yang ditunjuk
			'field' => 'id_prosrenmil', // nama kolom yang ditunjuk
			'display' => 'nama_prosrenmil', // nama kolom dari table yg dijadikan display option
		),
	);

	public function getDataSkenario()
	{
		$table = new Zend_Db_Table('latihan.skenario');
		$query = $table->select()
			->from('latihan.skenario', array('id', 'nomor'));
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

    public function getlogskenario($id)
    {
        $table = new Zend_Db_Table('user.logins');
        $query = $table->select()->setIntegrityCheck(false)
                    ->from(array('log' => 'user.logins'), array('log.id'))
                    ->join(array('tim' => 'user.Team'), 'tim."id" = log."id_team"', array('tim.team_name'))
                    ->join(array('sken' => 'latihan.skenario'), 'sken."id" = tim."kode_skenario"', array('sken.nomor'))
                    ->where('sken."id" = '."'".$id."'")
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

    public function closeSkenario($id)
    {
        $table = new Zend_Db_Table('latihan.skenario');
        $data = array(
            'closed' => 1
        );

        $table->update($data, "id = '".$id."'");
    }
}
	