<?php

/**
 * Browse - Browse Controller
 * 
 * @author Bramus! <bramus@bram.us>
 * 
 */
		
class BrowseController extends PlonkController {
	
	/**
	 * The views allowed for this module
	 * @var array
	 */	
	protected $views = array(
		'all',
		'tag',
		'date'
	);

	
	/**
	 * The actions allowed for this module
	 * @var array
	 */
	protected $actions = array(
	);
	
	
	/**
	 * The number of links per page to show
	 */
	const limitPerPage = 15;
	
	
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

	
	private function processPagination($curPage, $numPages)
	{
		
		// get pagination
		$pagination = buildPaginationSequence($curPage, $numPages);
		
		// previous page link
		if ($curPage == 1)
			$this->pageTpl->assignOption('oNoPrevLink');
		else {
			$this->pageTpl->assignOption('oPrevLink');
			$this->pageTpl->assign('prevPage',	$curPage - 1);
		}
		
		// next page link
		if ($curPage == $numPages)
			$this->pageTpl->assignOption('oNoNextLink');
		else {
			$this->pageTpl->assignOption('oNextLink');
			$this->pageTpl->assign('nextPage',	$curPage + 1);
		}
		
		// pagination links
		$this->pageTpl->setIteration('iPagination');
		
		foreach ($pagination as $page)
		{	
			if ($page === '...')
			{
				$this->pageTpl->assignIterationOption('oMore');
			} else {
				if ($page == $curPage) $this->pageTpl->assignIterationOption('oActive');
				else $this->pageTpl->assignIterationOption('oNormal'); 
			}
			
			$this->pageTpl->assignIteration('page',	$page);
			$this->pageTpl->refillIteration('iPagination');
		}
		
		$this->pageTpl->parseIteration('iPagination');
		
		// assign first and last
		$this->pageTpl->assign('firstPage',	1);
		$this->pageTpl->assign('lastPage',	$numPages);
			
	}
	
	private function processTagsIntoTemplate(array $tagsList, $extra = '')
	{
		
		// has tags
		if (sizeof($tagsList) > 0)
		{
			
			$extra = (trim($extra) !== '') ? $extra . '+' : '';
			
			$tpl = new PlonkTemplate('modules/browse/layout/tagslist.tpl');
			
			$tpl->setIteration('iTags');
			
			foreach ((array) $tagsList as $tag) {
				$tpl->assignIteration('extratag', $extra);
				$tpl->assignIteration('tag', $tag['tag']);
				$tpl->assignIteration('qty', $tag['qty']);
				$tpl->refillIteration();
			}
			
			$tpl->parseIteration();
			
			$content = $tpl->getContent();
			
			$tpl = null;
		}
		
		// no tags
		else {
			
			$content = '<p class="mute">(none)</p>';
			
		}
		
		return $content;		
		
	}
	
	
	/**
	 * All links
	 */
	public function showAll()
	{
				
		// Get necessary vars
		
			$loggedIn	= (PlonkSession::exists('loggedIn') && (PlonkSession::get('loggedIn') === true));
			
			// Paging
			$curPage 	= max(1, isset($this->urlParts[2]) ? (int) $this->urlParts[2] : 1);
			$numLinks	= BrowseDB::getNumLinks($loggedIn);
			$numPages	= max(ceil((int) $numLinks / self::limitPerPage), 1);
			
			// numLinks is false, redirect to the installation script
			// (if it's installed yet without links imported, it should be 0)
			if ($numLinks === false)
				PlonkWebsite::redirect('/install');
				
			
			// Check vars for validity
			if (($curPage < 1) || ($curPage > $numPages))
				PlonkWebsite::redirect('/' . MODULE);
			
			// Get links for this page
			$links 		= BrowseDB::getLinks($curPage - 1, self::limitPerPage, $loggedIn);
		
			
		// Main Layout
		
			// assign vars in our main layout tpl
			$this->mainTpl->assign('pageTitle', 		'Yummy! &mdash; '.htmlentities(USERNAME).'&lsquo;s bookmarks ('.$curPage.'/'.$numPages.')');
			$this->mainTpl->assign('pageMeta', 			'<link rel="stylesheet" type="text/css" href="/modules/browse/css/browse.css" />' . PHP_EOL);
						
			// The logout link
			$this->processLogoutLink();
			
		// Page Layout
			
			// Ouput all links
			if (sizeof($links) === 0)
			{
				
				$this->pageTpl->assignOption('oNoLinks');
				
			} else {
			
				$this->pageTpl->assignOption('oHasLinks');
				
				$this->pageTpl->setIteration('iLinks');
				
				$prevDate = '';
				
				foreach ($links as $link)
				{
					
					$niceDate = date('d M y', strtotime($link['added']));
					
					$this->pageTpl->assignIteration('link',			$link['link']);
					$this->pageTpl->assignIteration('title',		$link['title']);
					$this->pageTpl->assignIteration('private',		$link['private']);
					$this->pageTpl->assignIteration('added',		strtotime($link['added']));
					$this->pageTpl->assignIteration('addedNice',	$niceDate);
					
					if ($prevDate !== $niceDate)
					{
						$this->pageTpl->assignIterationOption('newDate');
						$prevDate = $niceDate;
					}
					
					if (trim($link['description']) !== '')
					{
						$this->pageTpl->assignIterationOption('oDescription');
						$this->pageTpl->assignIteration('description',	$link['description']);
					}
					
					$tags = explode(',', $link['tags']);
					
					if (sizeof($tags) > 0)
					{
						
						$this->pageTpl->assignIterationOption('oHasTags');
						
						$this->pageTpl->setIteration('iTags', 'iLinks');
						
						foreach ($tags as $tag)
						{
							
							$this->pageTpl->assignIteration('tag',	$tag);
							$this->pageTpl->refillIteration('iTags');
							
						}
						
						$this->pageTpl->parseIteration('iTags');
						
					}
					
					$this->pageTpl->refillIteration('iLinks');
					
				}
				
				$this->pageTpl->parseIteration('iLinks');
				
			} 
			
			// The pagination
			$this->processPagination($curPage, $numPages);
			
			// Some needed vars
			$this->pageTpl->assign('numLinks', $numLinks);
			$this->pageTpl->assign('MODULE', MODULE);
			$this->pageTpl->assign('username', USERNAME);
			
			// The tags list
			$tagsList = BrowseDB::getTagslist($loggedIn);
			$this->pageTpl->assign('topTags', $this->processTagsIntoTemplate($tagsList));
			
	}
	
	
	/**
	 * Links for a specific tag
	 */
	public function showTag()
	{
		
		// Get necessary vars
		
			$loggedIn	= (PlonkSession::exists('loggedIn') && (PlonkSession::get('loggedIn') === true));
			$tags		= explode('+', (isset($this->urlParts[2]) ? $this->urlParts[2] : ''));
			$tags 		= array_map('urldecode',$tags);
						
			// check if tag(s) exist(s)!
			if (!BrowseDB::tagExists($tags, $loggedIn)) PlonkWebsite::redirect('/' . MODULE);
			
			// Paging
			$curPage 	= max(1, isset($this->urlParts[3]) ? (int) $this->urlParts[3] : 1);
			$numLinks	= BrowseDB::getNumLinksForTag($tags, $loggedIn);
			$numPages	= max(ceil((int) $numLinks / self::limitPerPage), 1);
			
			// numLinks is false, redirect to the installation script
			// (if it's installed yet without links imported, it should be 0)
			if ($numLinks === false)
				PlonkWebsite::redirect('/install');
			
			// Check vars for validity
			if (($curPage < 1) || ($curPage > $numPages))
				PlonkWebsite::redirect('/' . MODULE);
			
			// Get links for this page
			$links 		= BrowseDB::getLinksForTag($tags, $curPage - 1, self::limitPerPage, $loggedIn);
		
			// rework tag for output
			$tagsNice 	= implode('+', $tags);
			if ($tagsNice === '') $tagsNice = '(untagged)';
			
		// Main Layout
		
			// assign vars in our main layout tpl
			$this->mainTpl->assign('pageTitle', 		'Yummy! &mdash; '.htmlentities(USERNAME).'&lsquo;s '.htmlentities($tagsNice).' bookmarks ('.$curPage.'/'.$numPages.')');
			$this->mainTpl->assign('pageMeta', 			'<link rel="stylesheet" type="text/css" href="/modules/browse/css/browse.css" />' . PHP_EOL);
						
			// The logout link
			$this->processLogoutLink();
			
		// Page Layout
			
			// Ouput all links
			if (sizeof($links) === 0)
			{
				
				$this->pageTpl->assignOption('oNoLinks');
				
			} else {
			
				$this->pageTpl->assignOption('oHasLinks');
				
				$this->pageTpl->setIteration('iLinks');
				
				$prevDate = '';
				
				foreach ($links as $link)
				{
					
					$niceDate = date('d M y', strtotime($link['added']));
					
					$this->pageTpl->assignIteration('link',			$link['link']);
					$this->pageTpl->assignIteration('title',		$link['title']);
					$this->pageTpl->assignIteration('private',		$link['private']);
					$this->pageTpl->assignIteration('added',		strtotime($link['added']));
					$this->pageTpl->assignIteration('addedNice',	$niceDate);
					
					if ($prevDate !== $niceDate)
					{
						$this->pageTpl->assignIterationOption('newDate');
						$prevDate = $niceDate;
					}
					
					if (trim($link['description']) !== '')
					{
						$this->pageTpl->assignIterationOption('oDescription');
						$this->pageTpl->assignIteration('description',	$link['description']);
					}
					
					$linktags = explode(',', $link['tags']);
					
					if (sizeof($linktags) > 0)
					{
						
						$this->pageTpl->assignIterationOption('oHasTags');
						
						$this->pageTpl->setIteration('iTags', 'iLinks');
						
						$lctags = array_map('strtolower', $tags);
						
						foreach ($linktags as $ltag)
						{
							
							$this->pageTpl->assignIteration('tag',	$ltag);
							if (in_array(strtolower($ltag), $lctags)) $this->pageTpl->assignIterationOption('oActive');
							$this->pageTpl->refillIteration('iTags');
							
						}
						
						$this->pageTpl->parseIteration('iTags');
						
					}
					
					$this->pageTpl->refillIteration('iLinks');
					
				}
				
				$this->pageTpl->parseIteration('iLinks');
				
			} 
			
			// The pagination
			$this->processPagination($curPage, $numPages);
			
			// Other needed vars
			$this->pageTpl->assign('numLinks', 	$numLinks);
			$this->pageTpl->assign('MODULE', 	MODULE);
			$this->pageTpl->assign('username', 	USERNAME);
			$this->pageTpl->assign('tag', 		$tagsNice);
			$this->pageTpl->assign('tagForUri', $this->urlParts[2]);
			
			// The tags list
			$tagsList = BrowseDB::getTagslist($loggedIn);
			$this->pageTpl->assign('topTags', $this->processTagsIntoTemplate($tagsList));
			
			// Related tags
			$relatedTags = BrowseDB::getRelatedTagsForTag($tags, $loggedIn);
			$this->pageTpl->assign('relatedTags', $this->processTagsIntoTemplate($relatedTags, $this->urlParts[2]));
			
	}

	
}

// EOF