<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 5/23/12
 * Time: 2:24 PM
 * To change this template use File | Settings | File Templates.
 */

class Latihan_SheetController extends App_CrudController
{
	protected $_lastChanged;

	public function indexAction()
	{
		if($this->isAjax())
		{
			$this->getHelper('viewRenderer')->setNoRender(true);
			$this->getHelper('layout')->disableLayout();

			$dt = new Latihan_Model_Datatables_Sheet($this->_request->getParams());
			echo $dt->result();
		}
	}

	public function addAction()
	{
		if($this->isAjax())
		{
			$this->getHelper('viewRenderer')->setNoRender(true);
			$this->getHelper('layout')->disableLayout();

			$sheet = $this->_getParam( 's' );
			$lastid = $this->_getParam( 'id' );

			$identity = Zend_Auth::getInstance()->getStorage()->read();
			$table = new Latihan_Model_DbTable_Sheet();

			if($lastid)
				$table->update( array( 'sheet' => $sheet, 'id_login' => $identity->id, 'updated' => date('Y-m-d h:i:s')), "id_rol_excel = {$lastid}");
			else
				$inserted = $table->insert(array(  'sheet' => $sheet, 'id_login' => $identity->id, 'updated' => date('Y-m-d h:i:s')));

			echo $inserted;
		}

	}

	public function editAction()
	{
		$rol_id = $this->_getParam( 'rol_id' );

		$t = new Latihan_Model_DbTable_Sheet();
		$dt = $t->getRolByRolID($rol_id);
		$this->view->lastChanged = $dt['updated'];

		if($this->isAjax())
		{
			$this->getHelper('viewRenderer')->setNoRender(true);
			$this->getHelper('layout')->disableLayout();

			$sheet = $this->_getParam( 's' );

			$identity = Zend_Auth::getInstance()->getStorage()->read();
			$table = new Latihan_Model_DbTable_Sheet();

			$table->update( array( 'sheet' => $sheet, 'id_login' => $identity->id, 'updated' => date('Y-m-d h:i:s')), "id_rol_excel = {$rol_id}");
		}
	}

	public function delAction()
	{
		$this->_del(null, 'index', 'Latihan_Model_Crud_Sheet');
	}

	public function detailAction()
	{
		$id = $this->_request->getParam('rol_id');
		$this->view->id = $id;
		$t = new Latihan_Model_DbTable_Sheet();
		$dt = $t->getRolByRolID($id);

		$this->view->lastChanged = $dt['updated'];
	}


	// Penyedia data ke Datatables
	public function eventfeedAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$id = $this->_request->getParam('rol_id');
		$t = new Latihan_Model_DbTable_Sheet();

		echo $t->getSheetByRolID($id);
	}

	public function sheetUploadAction()
	{
		if($this->isAjax())
		{
			$this->getHelper('viewRenderer')->setNoRender(true);
			$this->getHelper('layout')->disableLayout();

			$dt = new Latihan_Model_Datatables_SheetUpload($this->_request->getParams());
			echo $dt->result();
		}
	}

	public function uploadSheetAction()
	{
		$crud = new Latihan_Model_SheetUpload();

		$form = $crud->form();

		$identity = Zend_Auth::getInstance()->getStorage()->read();
		$form->setDefaults(array('id_user' => $identity->id));
		if ($this->_request->isPost()) {

			if ($form->isValid($this->_request->getPost())) {

				$fileInfo = $form->filepath->getFileInfo();
				//the following will rename the file (I'm setting the upload dir in the form)
				$originalFilename = pathinfo($form->filepath->getFileName());
				$newFile = 'rol-file-' . uniqid();
				$newFilename = $newFile . '.' . $originalFilename['extension'];
				$newFilenameWF = $newFile . '.htm';
				$form->filepath->addFilter('Rename', $newFilename);

                /*
                * Edited by tajhul.faijin@sangkuriang.co.id 11 07 2012
                * changed :
                * try {
                *   $doc_file = "file:///" . str_replace("\\", "/", $fileInfo['filepath']['tmp_name']);
                *   $output_file = "file:///" . str_replace('\\', "/", APPLICATION_PATH . '/../public/rol/converted/' . $newFilenameWF);
                *   try {
                *       $this->excel2htm($doc_file,$output_file);
                *   } catch (Exception $e) {
                *       return $this->_redirector->gotoSimple('index');
                *   }
                *
                *   $form->filepath->receive();
                *
                *} catch (Exception $e) {
                *   return $this->_redirector->gotoSimple('sheet-upload');
                *}
                * TO :
                */
                $form->filepath->receive();

				$crud->setFromPost($this->_request->getPost(), $newFilename );
				$crud->save();
				$this->_redirector->gotoSimple('sheet-upload');
			}
		}
		$this->view->form = $form;
	}

	public function readSheetAction()
	{
        $id = $this->_request->getParam('rol_id');
        $t = new Latihan_Model_DbTable_SheetUpload();
        $dt = $t->getRolByRolID($id);
        $read = new Spreadsheet( $dt['filepath'] ); //Class Spreadsheet <- lihat di library

        /*
        * Edited by tajhul.faijin@sangkuriang.co.id 11 07 2012
        *
        * changed :
        * $this->view->id = $id;
        * $this->view->data = $dt;
        * TO :
        * */
		$this->view->id = $id;
        $this->view->file = $read->dump(true,true);
        $this->view->data = $dt;
    }

	public function delsheetuploadAction()
	{
		$this->_del(null, 'sheet-upload', 'Latihan_Model_SheetUpload');
	}

	public function MakePropertyValue($name,$value,$osm){
		$oStruct = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
		$oStruct->Name = $name;
		$oStruct->Value = $value;
		return $oStruct;
	}

	public function excel2htm($doc_url, $output_url){
		//Invoke the OpenOffice.org service manager
		$osm = new COM("com.sun.star.ServiceManager") or die ("Please be sure that OpenOffice.org is installed.\n");

		//Set the application to remain hidden to avoid flashing the document onscreen
		$args = array($this->MakePropertyValue("Hidden",true,$osm));

		//Launch the desktop
		$top = $osm->createInstance("com.sun.star.frame.Desktop");

		//Load the .doc file, and pass in the "Hidden" property from above
		$oWriterDoc = $top->loadComponentFromURL($doc_url,"_blank", 0, $args);

		//Set up the arguments for the PDF output
		$export_args = array($this->MakePropertyValue("FilterName","HTML (StarCalc)",$osm));

		//Write out the PDF
		$oWriterDoc->storeToURL($output_url,$export_args);

		$oWriterDoc->close(true);
	}

}

?>