<?php
/**
 * Halaman Keluar Masuk
 */
class IndexController extends App_Controller
{

	public function init()
	{
		/* Initialize action controller here */
		parent::init();
	}

	// kalau sudah login
	public function indexAction()
	{
		//$this->getHelper('viewRenderer')->setNoRender(true);
		//$this->getHelper('layout')->disableLayout();
        $this->redirectTo('home');
	}

	public function loginAction()
	{
		if (Zend_Auth::getInstance()->hasIdentity()) {
			return $this->_redirector->gotoSimple('index');
		}
		$request = $this->_request;
		if ($request->isPost()) {
			$username = $request->getPost('username');
			$password = $request->getPost('password');

			if ($username != "" && $password != "") {
				$authAdapter = $this->_getAuthAdapter();
				$authAdapter
					->setIdentity($username)
					->setCredential(sha1($password)) // hash di level app
				;
				$auth = Zend_Auth::getInstance();
				$result = $auth->authenticate($authAdapter);

				if ($result->isValid()) {
					$identity = $authAdapter->getResultRowObject();
					$authStorage = $auth->getStorage();
					$authStorage->write($identity);
					if ($request->getPost('remember')) {
						Zend_Session::rememberMe();
					}
					$this->_redirector->gotoSimple('index');
				} else {
					$this->view->alert = 'Username atau Password Anda salah';
				}
			} else {
				$this->view->alert = 'Username dan Password harus diisi';
			}
		}
		$this->getHelper('layout')->disableLayout();
	}
	
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirector->gotoSimple('login');
	}
}

