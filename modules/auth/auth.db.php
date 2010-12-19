<?php

/**
 * Auth - Auth DB Class / Model
 * 
 * @author Bramus! <bramus@bram.us>
 * 
 */

class AuthDB
{
	
	
	/**
	 * Performs the login
	 * 
	 * @param string $username
	 * @param string $password
	 * @return
	 */
	public static function login($username, $password)
	{
		/*
		// get DB
		$db = PlonkWebsite::getDB();
		
		// check login
		$loggedIn 	= $db->getVar(
						sprintf(
							'SELECT COUNT(*) FROM users WHERE username = "%s" AND password = "%s"',
							$db->escape((string) $username),
							$db->escape((string) $password)
						)
					);
		*/
		
		$loggedIn = ((string) $username !== '') && ((string) $username === (string) USERNAME) && ((string) $password !== '') && ((string) $password === (string) PASSWORD);
		
		// login not OK
		if (!$loggedIn) {
			
			// make sure nothing is in the session
			PlonkSession::destroy();
			 
			return false;
			
		}
		
		// login OK, with info fetched: store it
		PlonkSession::set('username', 		$username);
		PlonkSession::set('loggedIn', 		true);
		
		return true;
		
	}
	
	
	/**
	 * Performs the logout
	 * 
	 * @return
	 */
	public static function logout()
	{
		
		PlonkSession::destroy();
		
	}
	
}

// EOF