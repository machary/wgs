<?php
/**
 * CRUD untuk Kesatuan Marinir
 *
 * @author tajhul.faijin@sangkuriang.co.id
 */
 
class Cms_KesatuanmarinirController extends App_CrudController
{
    public function postDispatch(){}

	// halaman berisi Datatables
	public function indexAction()
	{
        $div = $this->_request->getParam('div');
        $this->view->div = $div;
	}
	
	// Penyedia data ke Datatables
	public function dataapiAction()
	{
        $this->disableViewAndLayout();
		/**/
		$dt = new Cms_Model_Datatables_Kesatuanmarinir($this->_request->getParams());
        $dt->setDivisi( $this->_request->getParam('div') );
		echo $dt->result();
		/**/
	}
	
	public function addAction()
	{
		$form = new Cms_Form_Kesatuanmarinir();        
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
                $divisi = $this->_request->getParam('div');
                //populate data
                $data = $this->_request->getPost();
                $data['divisi'] = $divisi;
                $model = new Cms_Model_KesatuanMarinir();
                $model->setFromPost($data);
                $model->save();
				$this->redirectTo( 'cms/kesatuanmarinir/index/div/' . $divisi);
			}
		}
		$this->view->form = $form;
	}
	
	public function editAction()
	{
        $divisi = $this->_request->getParam('div');
		$model = new Cms_Model_KesatuanMarinir($this->_request->getParam('id'));
		if ($model->exists()) {
			$form = new Cms_Form_Kesatuanmarinir();
			if ($this->_request->isPost()) {
				if ($form->isValid($this->_request->getPost())) {

                    $data = $this->_request->getPost();
                    $data['divisi'] = $divisi;

                    $model->setFromPost($data);
					$model->save();
                    $this->redirectTo( 'cms/kesatuanmarinir/index/div/' . $divisi);
				}
			} else {
                $form->populate( $model->fetchRow() );
                $this->view->form = $form;
                $this->view->edit = true;
                $this->render('add');
            }
		} else {
            $this->redirectTo( 'cms/kesatuanmarinir/index/div/' . $divisi);
		}

	}
	
	public function delAction()
	{
        parent::disableViewAndLayout();
        $model = new Cms_Model_KesatuanMarinir($this->_request->getParam('id'), null);
        $model->delete();
        $this->redirectTo( 'cms/kesatuanmarinir/index/div/' . $this->_request->getParam('div'));
	}

    public function viewAction()
    {
        $obj = new Cms_Model_KesatuanMarinir(null,$this->_request->getParam('id'));
        $this->view->obj = $obj;
    }

}