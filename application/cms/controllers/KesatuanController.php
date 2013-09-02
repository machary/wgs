<?php
/**
 * CRUD untuk Kesatuan
 *
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_KesatuanController extends App_CrudController
{
	// halaman berisi Datatables
	public function indexAction()
	{
	}
	
	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		
		/**/
		$dt = new Cms_Model_Datatables_Kesatuan($this->_request->getParams());
		echo $dt->result();
		/**/
	}
	
	public function addAction()
	{
		$form = new Cms_Form_Kesatuan();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
                $model = new Cms_Model_DbTable_Kesatuan();
                $model->insertYonif($form->getValues());
				$this->_redirect('cms/kesatuan');
			}
		}
		$this->view->form = $form;

	}
	
	public function editAction()
	{
        $id = $this->_request->getParam('id');
        $form = new Cms_Form_Kesatuan();

        $model = new Cms_Model_DbTable_Kesatuan();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $model->updateYonif($form->getValues(), $id);
                $this->_redirect('cms/kesatuan');
            }
        }
        else
        {
            $dataYonif = $model->getYonif($id);
            $dataYonif['geom'] = 'point('.$dataYonif['longitude'].' '.$dataYonif['latitude'].')';
            $form->populate($dataYonif);
        }

        $this->view->form = $form;
	}
	
	public function delAction()
	{
        $id_yonif = $this->_request->getParam('id');
        $model = new Cms_Model_DbTable_Kesatuan();
        $model->deleteYonif($id_yonif);
        $this->_redirect('cms/kesatuan');
	}

    public function viewAction()
    {
        $id_yonif = $this->_request->getParam('id');
        $model = new Cms_Model_DbTable_Kesatuan();

        $dataYonif = $model->getYonif($id_yonif);

        $this->view->dataYonif = $dataYonif;
    }

}