<?php
/**
 * Model CRUD untuk table telegram
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_TelegramCrud extends Cms_Model_Crud
{
	protected $_primary = 'idtelegram';
	protected $_tableSchema = 'master';
	protected $_tableName = 'telegrams';
	protected $_exceptForm = array(
		'pengirim_id'   => array('tampil' => false,),
		'kepada_id'     => array('tampil' => false,),
        'tembusan_id'   => array('tampil' => false,),
        'team_id'       => array('tampil' => false,),
		'datetime'      => array('tampil' => false,),
	);

	public function form()
	{
        //pembeda user wasdal dengan user lain
        //get login identity
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $classwasdal = 'tidak_aktif';
        if ($identity->role_id == 9){
            $classwasdal = '';
        }

        //create form element
		$f = new Zend_Form();

		$f->setMethod('post')
			->setEnctype('multipart/form-data')
			->setDecorators(array(
            array('ViewScript',
                array('viewScript' => 'telegram/form.phtml')
            )
        ))
            ->addElement('text', 'header_telegram', array(
            'label' => 'Header Telegram',
            'required' => true,
            'size' => '50',
            'id' => 'id_header_telegram'
            //'class' => $classwasdal,
        ))
            ->addElement('text', 'header_telegram2', array(
            'label' => 'Header Telegram 2',
            'required' => true,
            'size' => '50',
            'id' => 'id_header_telegram2'
            //'class' => $classwasdal,
        ))
			->addElement('text', 'nomor_telegram', array(
			'label' => 'Nomor Telegram',
			'required' => true,
            'size' => '50',
			//'class' => $classwasdal,
		))
			->addElement('text', 'pengirim', array(
			'label' => 'Pengirim',
			'required' => true,
            'size' => '50',
			//'class' => $classwasdal,
		))
			->addElement('hidden', 'kepada', array(
			'required' => false,
            'size' => '50',
		))
			->addElement('hidden', 'tembusan', array(
			'required' => false,
            'size' => '50',
		))
			->addElement('hidden', 'isdraft', array(
			'required' => true,
			'value' => true,
		))
			->addElement('hidden', 'pengirim_id', array(
			'required' => true,
		))
			->addElement('hidden', 'kepada_id', array(
			'required' => false,
		))
			->addElement('hidden', 'tembusan_id', array(
			'required' => false,
		))
            ->addElement('select', 'rahasia', array(
                'label' => 'Tingkat Rahasia :',
                'multioptions' => array('TBA' => 'Terbuka','TBT' => 'Terbatas','RHS' => 'Rahasia')
            ))

            ->addElement('select', 'segera', array(
                'label' => 'Derajat :',
                'multioptions' => array('SGR' => 'Segera','SGT' => 'Sangat Segera')
        ))
			->addElement('textarea', 'isi_berita', array(
				'label' => 'Isi Berita',
				'required' => true,
				'class' => 'mceEditor'
        ))
            ->addElement('text', 'jadwal', array(
            'label' => 'Datetime',
            'required' => false,
            'class' => 'tgl',
        ))
            ->addElement('hidden', 'team_id', array(
            'required' => true,
        ))
            ->addElement('text', 'nama_pengirim', array(
            'label' => 'Nama Pengirim',
            'required' => true,
            'size' => '50',
            //'class' => $classwasdal,
        ))
            ->addElement('text', 'jabatan_pengirim', array(
            'label' => 'Jabatan Pengirim',
            'required' => true,
            'size' => '50',
            //'class' => $classwasdal,
        ))
            ->addElement('text', 'nama_kepada', array(
            'label' => 'Kepada',
            'required' => true,
            'size' => '70',
            //'class' => $classwasdal,
        ))
            ->addElement('text', 'nama_tembusan', array(
            'label' => 'Tembusan',
            'required' => true,
            'size' => '70',
            //'class' => $classwasdal,
        ))
            ->addElement('text', 'twu', array(
            'label' => 'TWU',
            'required' => true,
            'size' => '50',
            //'class' => $classwasdal,
        ))
            ->addElement('text', 'twb', array(
            'label' => 'TWB',
            'required' => true,
            'size' => '50',
            //'class' => $classwasdal,
        ))
    		// terakhir ditambahkan tombol submit
			->addElement('submit', 'submit', array(
			'ignore' => true,
			'label'  => 'Kirim',
		))
            ->addElement('hidden', 'folder', array(
            'required' => false,
        ))
		;

		return $f;
	}

	public function generate_NoTel()
	{
		return 'tele'.date('YzHis');
	}


    //menyatakan telegram itu sudah dibaca oleh user
	public function sudahbaca()
	{
		$identity = Zend_Auth::getInstance()->getStorage()->read();

		$table = new Zend_Db_Table('user.login_telegram');
		$query = $table->select()
			->from('user.login_telegram')
			->where('id_telegram = ?', $this->_id)
			->where('id_login = ?', $identity->id)
			;
		$ada = $table->fetchAll($query)->count();

		if (!$ada) {
			$table->insert(array('id_telegram'=> $this->_id,'id_login'=> $identity->id));
		}
	}

	public function kirimdraf()
	{
		$table = new Zend_Db_Table('master.telegrams');

		$data = array(
            'datetime' => date( "Y-m-d H:i:s" ),
			'isdraft' => 0
		);

		$where = $table->getAdapter()->quoteInto('idtelegram = ?', $this->_id);

		$table->update($data, $where);
	}


	public function getforprint()
	{
		if (isset($this->_id)){
			$table = new Zend_Db_Table('master.telegrams');
			$query = $table->select()->setIntegrityCheck(false)
				->from(array('teleg' => 'master.telegrams'))
				->joinLeft(array('tim' => 'user.Team'), 'tim."id" = teleg."team_id"')
				->joinLeft(array('ske' => 'latihan.skenario'), 'ske."id" = tim."kode_skenario"')
				->where('teleg."idtelegram" = ?', $this->_id)
			;
			return $table->fetchRow($query)->toArray();
		}else{
			return null;
		}
	}

    public function getTelegram($id)
    {
        $table = new Zend_Db_Table('master.telegrams');

        $query = $table->select()->setIntegrityCheck(false)
                    ->from('master.telegrams')
                    ->where('idtelegram = ?', $id)
        ;

        $result = $table->fetchRow($query);
        return (!empty($result)) ? $result->toArray() : null;
    }

    public function getHari($id)
    {
        $table = new Zend_Db_Table('master.folder_telegram');
        $query = $table->select()->setIntegrityCheck(false)
                        ->from('master.folder_telegram')
                        ->where('parent_id = ?', $id);

        $result = $table->fetchAll($query);
        return (!empty($result)) ? $result->toArray() : null;
    }
}