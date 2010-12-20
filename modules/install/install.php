<?php

/**
 * Install - Install Controller
 * 
 * @author Bramus! <bramus@bram.us>
 * 
 */
		
class InstallController extends PlonkController {
	
	/**
	 * The views allowed for this module
	 * @var array
	 */	
	protected $views = array(
		'prerequisites',
		'install',
		'done'
	);

	
	/**
	 * The actions allowed for this module
	 * @var array
	 */
	protected $actions = array(
		'upload'
	);
	
	
	/**
	 * The errors encountered after having submitted a form
	 * @var array
	 */
	private $formErrors = array();
	
	
	/**
	 * Processes the upload an exported file
	 */
	public function doUpload()
	{
		
		// User must be logged in
		if (!PlonkSession::exists('loggedIn') || (PlonkSession::get('loggedIn') !== true))
			PlonkWebsite::redirect('/install');
			
		// No file set
		if (!isset($_FILES['file']) || !isset($_FILES['file']['tmp_name']) || ($_FILES['file']['tmp_name'] == false))
		{
			
			// set error
			$this->formErrors[] = 'Please select a file to upload';
				
		}
	
		else
		{
			// get lines
			$lines = @file($_FILES['file']['tmp_name']);
			
			if (sizeof($lines) <= 1)
			{
				$this->formErrors[] = 'The file you have uploaded is empty';
			}
			
			else {
			
				$numLinks = InstallDB::importBackup($lines);
				
				PlonkWebsite::redirect('/install/done/' . $numLinks);
				
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
		
		// we are logged in, show it
		if (PlonkSession::exists('loggedIn') && (PlonkSession::get('loggedIn') === true))
		{
			
			// assign the logout link option
			$this->mainTpl->assign('logoutLink', '/auth/logout');
			$this->mainTpl->assign('username', PlonkSession::get('username'));
			$this->mainTpl->assignOption('oLoggedIn');
			
		}
		
		// we are not logged in
		else
		{
			
			// assign the logout link option
			$this->mainTpl->assign('loginLink', '/auth/login');
			$this->mainTpl->assignOption('oNotLoggedIn');
			
		
		}
		
		
	}

	
	public function showPrerequisites()
	{
			
		// Prerequisites have been checked before, redirect to install view
			if (PlonkSession::exists('prerequisites') && (PlonkSession::get('prerequisites') === true))
				PlonkWebsite::redirect('/' . MODULE . '/install');
			
		// Main Layout
		
			// assign vars in our main layout tpl
			$this->mainTpl->assign('pageTitle', 		'Yummy! &mdash; Installation &mdash; Prerequisites');
			$this->mainTpl->assign('pageMeta', 			'<link rel="stylesheet" type="text/css" href="/modules/install/css/install.css" />' . PHP_EOL);
						
			// The logout link
			$this->processLogoutLink();
			
		// Page Layout
		
			// Do checks
			$checks['username'] = (defined('PASSWORD') && (trim((string) PASSWORD) !== '') && defined('USERNAME') && (trim((string) USERNAME) !== ''));
			$checks['database'] = InstallDB::checkDb();
			$checks['sql'] 		= InstallDB::checkSql();
			
			// Assign shizzle
			if ($checks['username']) $this->pageTpl->assignOption('oConfigUsername');
			if ($checks['database']) $this->pageTpl->assignOption('oConfigDatabase');
			if ($checks['sql']) $this->pageTpl->assignOption('oConfigSql');
			if (!in_array(false, $checks)) $this->pageTpl->assignOption('oConfigOk');
			if (in_array(false, $checks)) $this->pageTpl->assignOption('oConfigNotOk');
			
			// set session thingy
			PlonkSession::set('prerequisites', !in_array(false, $checks));
		
	}
	
	
	public function showInstall()
	{
			
		// Prerequisites have not been checked yet, redirect to prerequisites view
			if (!PlonkSession::exists('prerequisites') || (PlonkSession::get('prerequisites') !== true))
				PlonkWebsite::redirect('/' . MODULE);
		
		// User must be logged in
			if (!PlonkSession::exists('loggedIn') || (PlonkSession::get('loggedIn') !== true))
				PlonkWebsite::redirect('/auth/login/install');
		
		// Main Layout
		
			// assign vars in our main layout tpl
			$this->mainTpl->assign('pageTitle', 		'Yummy! &mdash; Installation &mdash; Installing');
			$this->mainTpl->assign('pageMeta', 			'<link rel="stylesheet" type="text/css" href="/modules/install/css/install.css" />' . PHP_EOL);
						
			// The logout link
			$this->processLogoutLink();
			
		// Page Layout
			
			// process errors
			$this->processErrors();
		
			// formUrl
			$this->pageTpl->assign('formUrl', 	'/' . MODULE . '/install');
			
			// possibly installed already?
			if (InstallDB::possiblyInstalled()) $this->pageTpl->assignOption('linksFound');
			
	}
	
	public function showDone()
	{
			
		// Main Layout
		
			// assign vars in our main layout tpl
			$this->mainTpl->assign('pageTitle', 		'Yummy! &mdash; Installation &mdash; Installation Complete');
			$this->mainTpl->assign('pageMeta', 			'<link rel="stylesheet" type="text/css" href="/modules/install/css/install.css" />' . PHP_EOL);
						
			// The logout link
			$this->processLogoutLink();
			
		// Page Layout
		
			// qty
			$this->pageTpl->assign('qty', (isset($this->urlParts[2]) ? (int) $this->urlParts[2] : 0));
		
	}
	
}

// EOF