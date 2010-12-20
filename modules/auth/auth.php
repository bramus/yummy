<?php

/**
 * Auth - Auth Controller
 * 
 * @author Bramus! <bramus@bram.us>
 * 
 */
		
class AuthController extends PlonkController {

	/**
	 * The views allowed for this module
	 * @var array
	 */	
	protected $views = array(
		'login',
		'logout'
	);

	
	/**
	 * The actions allowed for this module
	 * @var array
	 */
	protected $actions = array(
		'login'
	);
	
	
	/*
	 * The errors
	 * @var array
	 */
	private $formErrors = array();
	
	
	/**
	 * Form vars
	 * @var string
	 */
	private $username = '', $password = '', $from = '';
	
	
	/**
	 * Login action
	 */
	public function doLogin()
	{
				
		// values we'll need
		$this->username   	= (string) PlonkFilter::getPostValue('username');
		$this->password		= (string) PlonkFilter::getPostValue('password');
		$this->from			= (string) PlonkFilter::getPostValue('from');
		
		// Check form: what not filled in
		if (trim($this->username) === '')
		{

			// set error
			$this->formErrors[] = 'Please enter your username';
			
		}
		
		// Check form: what not filled in
		if (trim($this->password) === '')
		{

			// set error
			$this->formErrors[] = 'Please enter your password';
			
		}
		
		// form correctly filled: try to log in
		if (sizeof($this->formErrors) == 0)
		{
			
			// Perfor the login
			AuthDB::login($this->username, $this->password);
			
			// Login succeeded: redirect to the the default view (change)
			if (PlonkSession::exists('loggedIn') && (PlonkSession::get('loggedIn') === true))
			{
				PlonkWebsite::redirect('/' . urlencode($this->from));
			}
			
			// login failed
			else
			{

				// set error
				$this->formErrors[] = 'Invalid username and/or password';
				
			}
			
		}	
		
	}


	/**
	 * Processes the errors
	 */
	public function processErrors()
	{
		
		// Ooh, we've got errors
		if (sizeof($this->formErrors) > 0)
		{
			
			// assign the option
			$this->pageTpl->assignOption('oErrors');
			
			// set the iteration
			$this->pageTpl->setIteration('iErrors');
		
			// loop all items and store 'm into the template iteration
			foreach ($this->formErrors as $error)
			{
				
				// assign vars
				$this->pageTpl->assignIteration('error', 	$error);
				
				// refill iteration
				$this->pageTpl->refillIteration();
				
			}
			
			// parse iteration
			$this->pageTpl->parseIteration();
			
		}
		
	}
	
	
	/**
	 * Shows (or doesn't show) the logout link
	 * @return 
	 */
	public function processLogoutLink()
	{
		
		// you need to log in
		if ($this->from != '')
			$this->mainTpl->assignOption('oNeedToLogIn');
		else
			$this->mainTpl->assignOption('oNotLoggedIn');
			
		// loginlink
		$this->mainTpl->assign('loginLink', '/auth/login');
		
	}
	
	/**
	 * The login view
	 */
	public function showLogin()
	{
			
		// if we are logged in, go to home
		if (PlonkSession::exists('loggedIn') && (PlonkSession::get('loggedIn') === true))	PlonkWebsite::redirect('/');
		
		// get from (if set via URL)
		$this->from = ($this->from == '') ? (isset($this->urlParts[2]) ? $this->urlParts[2] : '') : $this->from;
				
		// Main Layout
		
			// assign vars in our main layout tpl
			$this->mainTpl->assign('pageTitle', 		'Yummy! - Log in');
			$this->mainTpl->assign('pageMeta', 			'<link rel="stylesheet" type="text/css" href="/modules/auth/css/auth.css" />' . PHP_EOL . '<script type="text/javascript" src="/modules/auth/js/login.js"></script>');
			
			// The logout link
			$this->processLogoutLink();
				
		// Page Layout
			
			// The errors
				$this->processErrors();
				
			// the already filled in stuff
				$this->pageTpl->assign('from', 			$this->from);
				$this->pageTpl->assign('username', 		$this->username);
				$this->pageTpl->assign('password', 		$this->password);
				
			// form URL
				$this->pageTpl->assign('formUrl', 	'/auth/login');

	}
	
	
	/**
	 * The logout view
	 */
	public function showLogout()
	{
		
		// log out
		AuthDB::logout();
		
		// redirect to the login page
		PlonkWebsite::redirect('/');
		
	}

	
}

// EOF