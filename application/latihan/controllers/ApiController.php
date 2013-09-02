<?php
/**
 * <Controller untuk menghandle permintaan2 data & tidak perlu autentikasi>
 * @author Tajhul
 */
 
class Latihan_ApiController extends Zend_Controller_Action
{
    public function init()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }

    public function indexAction()
    {}

	public function simbolTaktisAction()
	{
        $jenis = $this->_request->getPost('jenis', 'linud' );

        if( !empty($jenis) ){
            $simbolModel = new Latihan_Model_DbTable_Simboltaktis();
            $data = $simbolModel->getAll('jenis', $jenis );
            echo json_encode( $data );
        }
	}

    /*
     * <markersAction>
     * Fungsi untuk menyuplai data marker untuk kekuatan sendiri
     * @author : tajhul
     * */
    public function markerSelfpowerAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
        $model = new Latihan_Model_DbTable_Simboltaktis();

        $layer_id = $_POST['layer_id'];
        $skenarioID = $_POST['skenarioId'];

        $markers = array();
        switch( strtolower($layer_id) )
        {
            case 1:
                $name = 'latihan.kekuatan_sendiri_laut';
                $nameDetail = 'latihan.kekuatan_sendiri_laut_detail';

                $table = new Zend_Db_Table( $name );
                $tableDetail = new Zend_Db_Table( $nameDetail );
                $query = $table->select()->setIntegrityCheck(false)
                        ->from(array('laut'=> $name))
                        ->where('laut.skenario_id = ?', $skenarioID)
                ;
                $result = $table->fetchAll($query)->toArray();

                foreach($result as $i => $row){
                    $temp['longitude'] = $row['longitude'];
                    $temp['latitude'] = $row['latitude'];
                    $temp['nama'] = $row['nama'];
                    $temp['detail'] = array();

                    $query2 = $tableDetail->select()
                            ->setIntegrityCheck(false)
                            ->from(array('detail'=> $nameDetail))
                            ->join(array('taktis' => 'master.simbol_taktis'), 'taktis.id= detail.taktis_id', array('taktis.nama as nama_taktis','filepath','singkatan'))
                            ->where('detail.parent_id = ?', $row['id'])
                    ;
                    $resultDetail = $tableDetail->fetchAll($query2)->toArray();

                    foreach($resultDetail as $rowDetail){
                        $tempDetail['jumlah'] = $rowDetail['jumlah'];
                        $tempDetail['nama_taktis'] = $rowDetail['nama_taktis'];
                        $tempDetail['filepath'] = $rowDetail['filepath'];
                        $tempDetail['singkatan'] = $rowDetail['singkatan'];
                        $tempDetail['keterangan'] = $rowDetail['keterangan'];

                        array_push($temp['detail'], $tempDetail);
                    }

                    array_push( $markers, $temp );
                }
                break;
            case 2:
                $name = 'latihan.kekuatan_sendiri_udara';
                $nameDetail = 'latihan.kekuatan_sendiri_udara_detail';

                $table = new Zend_Db_Table( $name );
                $tableDetail = new Zend_Db_Table( $nameDetail );
                $query = $table->select()->setIntegrityCheck(false)
                        ->from(array('udara'=> $name))
                        ->join(array('bandara' => 'public.bandara'), 'bandara.gid= udara.bandara_id', array('bandara.y as latitude','bandara.x as longitude'))
                        ->where('udara.skenario_id = ?', $skenarioID)
                ;
                $result = $table->fetchAll($query)->toArray();

                foreach($result as $i => $row){
                    $temp['longitude'] = $row['longitude'];
                    $temp['latitude'] = $row['latitude'];
                    $temp['nama'] = $row['nama'];
                    $temp['detail'] = array();

                    $query2 = $tableDetail->select()
                            ->setIntegrityCheck(false)
                            ->from(array('detail'=> $nameDetail))
                            ->join(array('taktis' => 'master.simbol_taktis'), 'taktis.id= detail.taktis_id', array('taktis.nama as nama_taktis','filepath','singkatan'))
                            ->where('detail.parent_id = ?', $row['id'])
                    ;
                    $resultDetail = $tableDetail->fetchAll($query2)->toArray();

                    foreach($resultDetail as $rowDetail){
                        $tempDetail['jumlah'] = $rowDetail['jumlah'];
                        $tempDetail['nama_taktis'] = $rowDetail['nama_taktis'];
                        $tempDetail['filepath'] = $rowDetail['filepath'];
                        $tempDetail['singkatan'] = $rowDetail['singkatan'];
                        $tempDetail['keterangan'] = $rowDetail['keterangan'];

                        array_push($temp['detail'], $tempDetail);
                    }
                    array_push( $markers, $temp );
                }
                break;
            case 3:
                $name = 'latihan.kekuatan_sendiri_darat';
                $nameDetail = 'latihan.kekuatan_sendiri_darat_detail';

                $table = new Zend_Db_Table( $name );
                $tableDetail = new Zend_Db_Table( $nameDetail );
                $query = $table->select()->setIntegrityCheck(false)
                        ->from(array('darat'=> $name))
                        ->where('darat.skenario_id = ?', $skenarioID)
                ;
                $result = $table->fetchAll($query)->toArray();

                foreach($result as $i => $row){
                    $temp['longitude'] = $row['longitude'];
                    $temp['latitude'] = $row['latitude'];
                    $temp['nama'] = $row['nama'];
                    $temp['detail'] = array();

                    $query2 = $tableDetail->select()
                            ->setIntegrityCheck(false)
                            ->from(array('detail'=> $nameDetail))
                            ->join(array('taktis' => 'master.simbol_taktis'), 'taktis.id= detail.taktis_id', array('taktis.nama as nama_taktis','filepath','singkatan'))
                            ->where('detail.parent_id = ?', $row['id'])
                    ;
                    $resultDetail = $tableDetail->fetchAll($query2)->toArray();

                    foreach($resultDetail as $rowDetail){
                        $tempDetail['jumlah'] = $rowDetail['jumlah'];
                        $tempDetail['nama_taktis'] = $rowDetail['nama_taktis'];
                        $tempDetail['filepath'] = $rowDetail['filepath'];
                        $tempDetail['singkatan'] = $rowDetail['singkatan'];
                        $tempDetail['keterangan'] = $rowDetail['keterangan'];
                        array_push($temp['detail'], $tempDetail);
                    }

                    array_push( $markers, $temp );
                }
                break;
	        case 4:
                $name = 'latihan.kekuatan_musuh_laut';
                $nameDetail = 'latihan.kekuatan_musuh_laut_detail';

		        $table = new Zend_Db_Table( $name );
		        $tableDetail = new Zend_Db_Table( $nameDetail );
		        $query = $table->select()->setIntegrityCheck(false)
			        ->from(array('musuh'=> $name))
			        ->where('musuh."skenario_id" = '."'".$skenarioID."'")
		        ;
		        $result = $table->fetchAll($query)->toArray();

		        foreach($result as $i => $row){
			        $temp['longitude'] = $row['longitude'];
			        $temp['latitude'] = $row['latitude'];
			        $temp['nama'] = $row['nama'];
			        $temp['detail'] = array();

			        $query2 = $tableDetail->select()
				        ->setIntegrityCheck(false)
				        ->from(array('detail'=> $nameDetail))
				        ->join(array('taktis' => 'master.simbol_taktis'), 'taktis."id"= detail."taktis_id"', array('taktis.nama as nama_taktis','filepath','singkatan'))
				        ->where('detail.parent_id = ?', $row['id'])
			        ;
			        $resultDetail = $tableDetail->fetchAll($query2)->toArray();

			        foreach($resultDetail as $rowDetail){
				        $tempDetail['jumlah'] = $rowDetail['jumlah'];
				        $tempDetail['nama_taktis'] = $rowDetail['nama_taktis'];
				        $tempDetail['filepath'] = $rowDetail['filepath'];
				        $tempDetail['singkatan'] = $rowDetail['singkatan'];
                        $tempDetail['keterangan'] = $rowDetail['keterangan'];
				        array_push($temp['detail'], $tempDetail);
			        }

			        array_push( $markers, $temp );
		        }
		        break;
	        case 5:
                $name = 'latihan.kekuatan_musuh_udara';
                $nameDetail = 'latihan.kekuatan_musuh_udara_detail';

		        $table = new Zend_Db_Table( $name );
		        $tableDetail = new Zend_Db_Table( $nameDetail );
		        $query = $table->select()->setIntegrityCheck(false)
			        ->from(array('musuh'=> $name))
			        ->where('musuh."skenario_id" = '."'".$skenarioID."'")
		        ;
		        $result = $table->fetchAll($query)->toArray();

		        foreach($result as $i => $row){
			        $temp['longitude'] = $row['longitude'];
			        $temp['latitude'] = $row['latitude'];
			        $temp['nama'] = $row['nama'];
			        $temp['detail'] = array();

			        $query2 = $tableDetail->select()
				        ->setIntegrityCheck(false)
				        ->from(array('detail'=> $nameDetail))
				        ->join(array('taktis' => 'master.simbol_taktis'), 'taktis.id= detail.taktis_id', array('taktis.nama as nama_taktis','filepath','singkatan'))
				        ->where('detail.parent_id = ?', $row['id'])
			        ;
			        $resultDetail = $tableDetail->fetchAll($query2)->toArray();

			        foreach($resultDetail as $rowDetail){
				        $tempDetail['jumlah'] = $rowDetail['jumlah'];
				        $tempDetail['nama_taktis'] = $rowDetail['nama_taktis'];
				        $tempDetail['filepath'] = $rowDetail['filepath'];
				        $tempDetail['singkatan'] = $rowDetail['singkatan'];
                        $tempDetail['keterangan'] = $rowDetail['keterangan'];
				        array_push($temp['detail'], $tempDetail);
			        }

			        array_push( $markers, $temp );
		        }
		        break;
	        case 6:
		        $name = 'latihan.kekuatan_musuh_darat';
		        $nameDetail = 'latihan.kekuatan_musuh_darat_detail';

		        $table = new Zend_Db_Table( $name );
		        $tableDetail = new Zend_Db_Table( $nameDetail );
		        $query = $table->select()->setIntegrityCheck(false)
			        ->from(array('musuh'=> $name))
			        ->where('musuh."skenario_id" = '."'".$skenarioID."'")
		        ;
		        $result = $table->fetchAll($query)->toArray();

		        foreach($result as $i => $row){
			        $temp['longitude'] = $row['longitude'];
			        $temp['latitude'] = $row['latitude'];
			        $temp['nama'] = $row['nama'];
			        $temp['detail'] = array();

			        $query2 = $tableDetail->select()
				        ->setIntegrityCheck(false)
				        ->from(array('detail'=> $nameDetail))
				        ->join(array('taktis' => 'master.simbol_taktis'), 'taktis.id= detail.taktis_id', array('taktis.nama as nama_taktis','filepath','singkatan'))
				        ->where('detail.parent_id = ?', $row['id'])
			        ;
			        $resultDetail = $tableDetail->fetchAll($query2)->toArray();

			        foreach($resultDetail as $rowDetail){
				        $tempDetail['jumlah'] = $rowDetail['jumlah'];
				        $tempDetail['nama_taktis'] = $rowDetail['nama_taktis'];
				        $tempDetail['filepath'] = $rowDetail['filepath'];
				        $tempDetail['singkatan'] = $rowDetail['singkatan'];
                        $tempDetail['keterangan'] = $rowDetail['keterangan'];
				        array_push($temp['detail'], $tempDetail);
			        }

			        array_push( $markers, $temp );
		        }
		        break;
        }

        echo json_encode($markers);
    }

	public function simbolPergerakanAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$skenario_id = $_POST['skenario_id'];
//		$model = new Latihan_Model_DbTable_KekuatanMusuhPergerakan();
//		$data = $model->getPergerakan($skenario_id);

		switch($_POST['table'])
		{
			case 1:
				$tableName = 'latihan.kekuatan_musuh_pergerakan';
				break;
			case 2:
				$tableName = 'latihan.kekuatan_sendiri_pergerakan';
				break;
		}

		$table = new Zend_Db_Table($tableName);
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('gerak'=>$tableName))
			->joinLeft(array('sim'=>'master.simbol_pergerakan'), 'sim."id"=gerak."id_simbol_pergerakan"', array('sim.filepath'))
			->where("gerak.id_skenario = '".$skenario_id."'")
		;
		$result = $table->fetchAll($query)->toArray();

		echo json_encode($result);
	}

	public function opssimbolpergerakanAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$cb_id = $_POST['cb_id'];

		$table = new Zend_Db_Table('ops.gabla_cb');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('gabla'=>'ops.gabla_cb'))
			->joinLeft(array('intel'=>'intelijen_pergerakan'), 'intel."id_team"=gabla."team_id"')
			->joinLeft(array('gerak'=>'master.simbol_pergerakan'), 'gerak."id"=intel."id_simbol_pergerakan"', array('gerak.filepath'))
			->where("gabla.id= '".$cb_id."'")
		;
		$result = $table->fetchAll($query)->toArray();

		echo json_encode($result);
	}

    /*
     * <markersAction>
     * Fungsi untuk query data marker untuk kekuatan musuh
     * @author : irfan.muslim@sangkuriang.co.id
     * */
    public function markerEnemyforcesAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
        $model = new Latihan_Model_DbTable_Simboltaktis();

        $layer_id = $_POST['layer_id'];
        $skenarioID = $_POST['skenarioId'];

        $markers = array();
        switch( strtolower($layer_id) )
        {
            case 1:
                $name = 'latihan.kekuatan_musuh_laut';
                $nameDetail = 'latihan.kekuatan_musuh_laut_detail';

                $table = new Zend_Db_Table( $name );
                $tableDetail = new Zend_Db_Table( $nameDetail );
                $query = $table->select()->setIntegrityCheck(false)
                        ->from(array('darat'=> $name))
                        ->where('darat.skenario_id = ?', $skenarioID)
                ;
                $result = $table->fetchAll($query)->toArray();

                foreach($result as $i => $row){
                    $temp['longitude'] = $row['longitude'];
                    $temp['latitude'] = $row['latitude'];
                    $temp['nama'] = $row['nama'];
                    $temp['detail'] = array();

                    $query2 = $tableDetail->select()
                            ->setIntegrityCheck(false)
                            ->from(array('detail'=> $nameDetail))
                            ->join(array('taktis' => 'master.simbol_taktis'), 'taktis.id= detail.taktis_id', array('taktis.nama as nama_taktis','filepath','singkatan'))
                            ->where('detail.parent_id = ?', $row['id'])
                    ;
                    $resultDetail = $tableDetail->fetchAll($query2)->toArray();

                    foreach($resultDetail as $rowDetail){
                        $tempDetail['jumlah'] = $rowDetail['jumlah'];
                        $tempDetail['nama_taktis'] = $rowDetail['nama_taktis'];
                        $tempDetail['filepath'] = $rowDetail['filepath'];
                        $tempDetail['singkatan'] = $rowDetail['singkatan'];
                        array_push($temp['detail'], $tempDetail);
                    }

                    array_push( $markers, $temp );
                }
                break;
            case 2:
                $name = 'latihan.kekuatan_musuh_udara';
                $nameDetail = 'latihan.kekuatan_musuh_udara_detail';

                $table = new Zend_Db_Table( $name );
                $tableDetail = new Zend_Db_Table( $nameDetail );
                $query = $table->select()->setIntegrityCheck(false)
                        ->from(array('darat'=> $name))
                        ->where('darat.skenario_id = ?', $skenarioID)
                ;
                $result = $table->fetchAll($query)->toArray();

                foreach($result as $i => $row){
                    $temp['longitude'] = $row['longitude'];
                    $temp['latitude'] = $row['latitude'];
                    $temp['nama'] = $row['nama'];
                    $temp['detail'] = array();

                    $query2 = $tableDetail->select()
                            ->setIntegrityCheck(false)
                            ->from(array('detail'=> $nameDetail))
                            ->join(array('taktis' => 'master.simbol_taktis'), 'taktis.id= detail.taktis_id', array('taktis.nama as nama_taktis','filepath','singkatan'))
                            ->where('detail.parent_id = ?', $row['id'])
                    ;
                    $resultDetail = $tableDetail->fetchAll($query2)->toArray();

                    foreach($resultDetail as $rowDetail){
                        $tempDetail['jumlah'] = $rowDetail['jumlah'];
                        $tempDetail['nama_taktis'] = $rowDetail['nama_taktis'];
                        $tempDetail['filepath'] = $rowDetail['filepath'];
                        $tempDetail['singkatan'] = $rowDetail['singkatan'];
                        array_push($temp['detail'], $tempDetail);
                    }

                    array_push( $markers, $temp );
                }
                break;
            case 3:
                $name = 'latihan.kekuatan_musuh_darat';
                $nameDetail = 'latihan.kekuatan_musuh_darat_detail';

                $table = new Zend_Db_Table( $name );
                $tableDetail = new Zend_Db_Table( $nameDetail );
                $query = $table->select()->setIntegrityCheck(false)
                        ->from(array('darat'=> $name))
                        ->where('darat.skenario_id = ?', $skenarioID)
                ;
                $result = $table->fetchAll($query)->toArray();

                foreach($result as $i => $row){
                    $temp['longitude'] = $row['longitude'];
                    $temp['latitude'] = $row['latitude'];
                    $temp['nama'] = $row['nama'];
                    $temp['detail'] = array();

                    $query2 = $tableDetail->select()
                            ->setIntegrityCheck(false)
                            ->from(array('detail'=> $nameDetail))
                            ->join(array('taktis' => 'master.simbol_taktis'), 'taktis.id= detail.taktis_id', array('taktis.nama as nama_taktis','filepath','singkatan'))
                            ->where('detail.parent_id = ?', $row['id'])
                    ;
                    $resultDetail = $tableDetail->fetchAll($query2)->toArray();

                    foreach($resultDetail as $rowDetail){
                        $tempDetail['jumlah'] = $rowDetail['jumlah'];
                        $tempDetail['nama_taktis'] = $rowDetail['nama_taktis'];
                        $tempDetail['filepath'] = $rowDetail['filepath'];
                        $tempDetail['singkatan'] = $rowDetail['singkatan'];
                        array_push($temp['detail'], $tempDetail);
                    }

                    array_push( $markers, $temp );
                }
                break;
        }

        echo json_encode($markers);
    }

    public function simbolAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $id = $this->_getParam('id');
        $model = new Peta_Model_DbTable_Intelijen();

        if($id != '')
        {
            $url = $model->simbolPergerakan($id);
        }

        echo json_encode($url['filepath']);
    }
}
