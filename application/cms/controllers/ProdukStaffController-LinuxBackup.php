<?php
/**
 * Produk Staff Controller
 *
 * Untuk lihat dan tambah produk staf (file PDF)
 *
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_ProdukStaffController extends App_CrudController
{
	// halaman berisi Datatables
	public function indexAction()
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();
		$modLog = new Cms_Model_DbTable_ProductStaff();
        $lock = $modLog->selectLock($identity->id);
        $this->view->lock = $lock['lock'];
	}

	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();
		$dt = new Cms_Model_Datatables_ProdukStaff($this->_request->getParams());
		echo $dt->result();
	}

	public function addAction()
	{
		$crud = new Cms_Model_ProdukStaff(null);
        $log = new Cms_Model_DbTable_ProductStaff();
		$identity = Zend_Auth::getInstance()->getStorage()->read();

		$lock = $log->selectLock($identity->id);

		if($lock == 1) {
			$this->_redirector->gotoSimple('index');
		}

		$form = $crud->form();

		$form->setDefaults(array('id_user' => $identity->id));
		if ($this->_request->isPost()) {

			if ($form->isValid($this->_request->getPost())) {
				/**
				 * June 05 2012
				 * Edited by Febi
				 * Note: Convert Word ke PDF saat Upload [OpenOffice Harus Terisntal pada server]
				 *
				 * Changed:
				 * try {
				 * 	$form->filepath->receive();
				 * } catch (Exception $e) {
				 * 	return $this->_redirector->gotoSimple('index');
				 * }
				 *
				 */

                $model = new Cms_Model_DbTable_ProductStaff();
                $dataMod = $model->tipeskenario($identity->id_team);
                $data = array();

                //print_r($dataMod);exit;

                if(empty($dataMod[0]['prosrenmil_id']))
                {
                    $exist = $model->getMProdStaffName($form->idm_product_staff->getValue());
                    if(!empty($exist)){
                        $idmProdStaffVal = $exist['id'];
                    }  else {
                        $idmProdStaffVal = $model->addMProdStaff($form->idm_product_staff->getValue());
                    }

                    $formPost = $this->_request->getPost();
                    $formPost['idm_product_staff'] = $idmProdStaffVal;
                }
                else
                {
                    $text = $form->text_product_staff->getValue();
                    if(!empty($text)) {
                        $exist = $model->getMProdStaffName($text);
                        if(!empty($exist)){
                            $idmProdStaffVal = $exist['id'];
                        }  else {
                            $idmProdStaffVal = $model->addMProdStaff($text);
                        }
                        $formPost = $this->_request->getPost();
                        $formPost['idm_product_staff'] = $idmProdStaffVal;
                    } else {
                        $formPost = $this->_request->getPost();
                        $idmProdStaffVal = $form->idm_product_staff->getValue();
                    }
                    unset($formPost['text_product_staff']);
                }

				$fileInfo = $form->filepath->getFileInfo();
				//the following will rename the file (I'm setting the upload dir in the form)
				$namaProductStaff = $crud->_mprodstaffSelected($idmProdStaffVal);
				$originalFilename = pathinfo($form->filepath->getFileName());
				$newName = str_replace(' ', '', $namaProductStaff) . '-file-' . uniqid();
				$newFilename = $newName . '.' . $originalFilename['extension'];
				$newFilenameWF = $newName  . '.pdf';
				$form->filepath->addFilter('Rename', $newFilename);

//-------------------------------------------- Linux code ------------------
				
				if($originalFilename['extension'] == 'pdf')
				{
					try {
						$form->filepath->receive();
						$newFilenameWF = $newFilename;
					} catch (Exception $e) {
						return $this->_redirector->gotoSimple('index');
					}
				} else {
					
					/**
					 * Edited by Tajhul Faijin Aliyudin
					 * menggunakan web service di url http://10.1.1.103:81 untuk melakukan konversi file
					 * @param ?file=filename.ext
					 * @full_url = http://10.1.1.103:81/?file=testing.docx
					 * 
					 * NOTE : 
					 * - direktori aplikasi web-converter ada di /home/seskoal/doc/
					 * - pastikan folder /wgs/public/pstaff itu milik user seskoal (cek pake ls -l, set pake chown seskoal:seskoal -R pstaff)
					 * 
					 * IMPORTANT! 
					 * - jangan hapus apapun di /home/seskoal/doc/
					 * - jangan unistall libreoffice-NYA!
					 */
					if($form->filepath->receive()){
						$urlconverter = 'http://10.1.1.103:81/?file='. $newFilename;
						fopen($urlconverter,'r');	
					}								
				}

//-------------------------------------------- End Linux Code --------------------------------------------------

                $id_team = $log->getIdTeam($identity->id);
                $teams = $log->getTeamId($id_team['id_team']);

                foreach($teams as $tim)
                {
                    $log->lock($tim['id']);
                }

				/**
				 * Edited by Febi
				 * NOTE: dengan perubahan file upload di atas, file yang
				 *		terupload bertempat di ./public, maka dibuat function
				 *		setFromPost
				 *
				 * changed:
				 * $crud->setFromFrom( $form );
				 *
				 */

				$crud->setFromPost($formPost, $newFilenameWF, $newFilename );
				$crud->save();
                $this->_helper->flashMessenger->addMessage('Produk staff sudah terupload');
				$this->_redirector->gotoSimple('index');
			}
		}
		$this->view->form = $form;
	}

	public function delAction()
	{
		$log = new Cms_Model_DbTable_ProductStaff();
		$identity = Zend_Auth::getInstance()->getStorage()->read();

		$id_team = $log->getIdTeam($identity->id);
		$teams = $log->getTeamId($id_team['id_team']);

		foreach($teams as $tim)
		{
			$log->unlock($tim['id']);
		}

		$this->_del(null, 'index', 'Cms_Model_ProdukStaff');
	}

	// online PDF viewer
	public function readAction()
	{
		// pakai halaman khusus
		$this->getHelper('layout')->setLayout('layout-less');

		$ref = new Cms_Model_ProdukStaff(null, $this->_request->getParam('id'));
		if ($ref->exists()) {
			$this->view->ref = $ref;
		} else {
			$this->_redirector->gotoSimple('index');
		}
	}

	// download lewat sini
	public function downloadAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		$ref = new Cms_Model_ProdukStaff(null, $this->_request->getParam('id'));
		if ($ref->exists()) {
			$this->_redirector->gotoUrl($ref->get('original_filepath'));
		} else {
			$this->_redirector->gotoSimple('index');
		}
	}

	//get list langkah untuk dropdownlistbox
	public function langkahOptAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();
		$prosrenmil = $this->_request->getPost( 'prosrenmil' );

		$langkah = new Cms_Model_ProdukStaff();
		$arrlangkah = $langkah->getLangkah($prosrenmil);

		echo json_encode($arrlangkah);
	}

	public function MakePropertyValue($name,$value,$osm){
		$oStruct = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
		$oStruct->Name = $name;
		$oStruct->Value = $value;
		return $oStruct;
	}

	public function word2pdf($doc_url, $output_url){
		//Invoke the OpenOffice.org service manager
		$osm = new COM("com.sun.star.ServiceManager") or die ("Please be sure that OpenOffice.org is installed.\n");

		//Set the application to remain hidden to avoid flashing the document onscreen
		$args = array($this->MakePropertyValue("Hidden",true,$osm));

		//Launch the desktop
		$top = $osm->createInstance("com.sun.star.frame.Desktop");

		//Load the .doc file, and pass in the "Hidden" property from above
		$oWriterDoc = $top->loadComponentFromURL($doc_url,"_blank", 0, $args);

		//Set up the arguments for the PDF output
		$export_args = array($this->MakePropertyValue("FilterName","writer_pdf_Export",$osm));

		//Write out the PDF
		$oWriterDoc->storeToURL($output_url,$export_args);

		$oWriterDoc->close(true);
	}
}