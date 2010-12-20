<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl">
<head>
	
	<title>{$pageTitle}</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	
	<link rel="stylesheet" type="text/css" media="screen" href="/core/css/reset.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="/core/css/screen.css" />
	<!--[if lte IE 6]><link rel="stylesheet" type="text/css" media="screen" href="/modules/core/layout/css/ie6.css" /><![endif]-->
	<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="/modules/core/layout/css/ie7.css" /><![endif]-->
	
	<script type="text/javascript" src="/core/js/jquery-1.4.2.min.js"></script>
	
	{$pageMeta}
	
</head>
<body>
	
	<div id="siteWrapper">
		
		<!-- header -->
		<div id="header">
			<h1><a href="/">Yummy! &mdash; A self hosted Delicious</a></h1>
			{option:oNotLoggedIn}<p id="loginbox">You are not logged in, private links are hidden <em>(<a href="{$loginLink|htmlentities}" title="login" id="loginLink">log in</a>)</em></p>{/option:oNotLoggedIn}
			{option:oLoggedIn}<p id="loginbox">Welcome, <strong>{$username}</strong> <em>(<a href="{$logoutLink|htmlentities}" title="logout" id="logoutLink">log out</a>)</em></p>{/option:oLoggedIn}
			{option:oNeedToLogIn}<p id="loginbox" class="note">You need to be logged in to do that</p>{/option:oNeedToLogIn}
		</div>
		
		<!-- content -->
		<div id="content">
			
			{$pageContent}
			
		</div>
		
		<!-- footer -->
		<div id="footer">
			<p>Yummy! &mdash; A self hosted Delicious &mdash; Built by <a href="http://www.bram.us/" title="Bramus!">Bramus!</a></p>
		</div>
		
	</div>
	
</body>
</html>