<?php
include_once(LIB_PATH . "Core/IAuthService.php");

/***********************************************
 * PMAuthService
 * Handles authentication, entry point access,
 * and checks if a user is logged in
 *
 * @author			Tyler Barnes
 * @author			Chris Schaefer
 * @contact			tbarnes@arbsol.com
 * @contact			cschaefer@arbsol.com
 * @version			1.0
 ***************************************************/
/*+++++++++++++++++++++++++++++++++++++++++++++++++*
 * 				Change Log
 *
 *+++++++++++++++++++++++++++++++++++++++++++++++++*/
class PMAuthService implements IAuthService {

	/*	PROPERTIES	*/
	private $_forceLogOutSeconds;

	/*	END PROPS	*/

	public function PMAuthService($forceLogOutSeconds) {
		$this->_forceLogOutSeconds = $forceLogOutSeconds;
	}

	/********************************************
	 * validEntryPoint
	 * validates app entry point
	 *
	 * @param path: string path to check
	 * @return bool true if valid
	 *******************************************/
	public function validEntryPoint($path = "") {
		return true;
	}

	/**********************************************
	 * isLoggedIn
	 * checks if user is logged in and logged in
	 * session has not expired
	 *
	 * @return bool true if logged in
	 **********************************************/
	public function isLoggedIn() {
		return true;
	}


}
?>
