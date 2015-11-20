<?php

/**************************************************
 * ProMan
 *
 * Base class for the application.
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
 *
 *+++++++++++++++++++++++++++++++++++++++++++++++++*/

class ProMan {

	/**		Properties		**/

	// configuration (default mvc values - add more to config if needed)
	private $_config_;
	// max time allowed to be logged in for per session
	private $_maxSessionTime_;
	// debug mode - influences logging behavior and such
	private $_debug_;

	// authentication service
	public $_authService;
	// controller
	public $_controller;
	// dbcon instance
	public $_dbAdapter;
	// cache service
	public $_cacheService;

	// defaults (should get from config unless config is not passed)
	public $_defaultModule;
	public $_defaultView;
	public $_defaultAction;

	/**	STATIC	**/

	public static $DEFAULT_CONFIG_MAP = array(
		"module"	=>	array(
					"urii"		=>	0,
					"default"	=>	"user",
				),
		"view"		=>	array(
					"urii"		=>	1,
					"default"	=>	"index",
				),
		"action"	=>	array(
					"urii"		=>	2,
					"default"	=>	"index",
				),
	);

	/**		END PROPS		**/




	/***************************
	 * C'TOR
	 * Constructs an application object
	 *
	 * @param config: array config vals (assoc array)
	 * @param maxLoggedInTime: int seconds of max session log in time
	 * @param debug: bool true means app will run in debug mode --- default false
	 ***************************/
	public function ProMan(array $config = array(), $maxLoggedInTime = 0, $debug = false) {


		// lets the app access these functions later
		$GLOBALS["APP"]["INSTANCE"] = $this;
	}


	public function Boot() {

	}


	private function Run() {


	}



	/************************************************
	 * Isolate
	 * Finds the module, view, action vars from the
	 * request URI
	 *
	 * @param uri_idx: int index number
	 * @return string value
	 *************************************************/
	private function Isolate($uri_idx) {
		$uri_cmpnts = explode("/", $_SERVER['REQUEST_URI']);

		foreach($uri_cmpnts as $idx => $cmpnt)
			if(!self::StringHasValue($cmpnt) || is_null($cmpnt))
				unset($uri_cmpnts[$idx]);

		if(count($uri_cmpnts) > $uri_idx)
			return $uri_cmpnts[array_keys($uri_cmpnts)[$uri_idx]];
		else
			return false;
	}


	/*************************************
	 * getDefaultconfig
	 * Gets default config
	 *
	 * @return array of config vals
	 **************************************/
	private function getDefaultConfig() {
		$ret = array();
		foreach(self::$DEFAULT_CONFIG_MAP as $item => $data) {
			$ret[$item] = $data["default"];
		}

		return $ret;
	}



	/********************************
	 * sets max session time allowed
	 * default 1 hour (3600 seconds)
	 *********************************/
	public function setMaxLoggedInTime($secs = DEFAULT_SESSION_TIME) {
		$this->_maxSessionTime_ = $secs;
	}




	private function HasUser() {
		return (isset($_SESSION["User"]) && gettype($_SESSION["User"]) == "User");
	}


	/*****************************************
	 * CleanUp
	 * clear resources and things
	 ****************************************/
	private function CleanUp() {
		return true;
	}


	/*********************************************
	 * SessionActivate
	 * starts a session
	 *
	 * @return bool true if success
	 **********************************************/
	public function SessionActivate() {
		if(isset($_SESSION["PHPSESSID"]) && $_SESSION["PHPSESSID"] == true && isset($_COOKIE["PHPSESSID"]) && !(is_null(session_id()))) {
			return false;
		}
		if(isset($_SESSION["PHPSESSID"])) unset($_SESSION["PHPSESSID"]);
		session_start();
		$_SESSION["PHPSESSID"] = true;
		return true;
	}


	/*********************************************
	 * SessionTerminate
	 * destroys a session
	 *********************************************/
	public function SessionTerminate() {
		if(isset($_SESSION["PHPSESSID"])) unset($_SESSION["PHPSESSID"]);
		if(isset($_SESSION["User"])) unset($_SESSION["User"]);
		if(session_id() != "") {
			session_destroy();
		}
	}



	public function CookieBake($name = null, $value = null, $expr = 1, $pth = "/", $domain = null, $secr = null, $httpOnly = null) {
		if(!isset($name) || empty($name)) return false;
		$expr = (time() + (3600 * $expr));
		$domain = (is_null($domain)) ? ((isset($_SERVER["HTTP_HOST"])) ? $_SERVER["HTTP_HOST"] : BASE_PTH) : $domain;
		setcookie($name, $value, $expr, $pth, $domain, $secr, $httpOnly);
	}

	public function CookieBurn($name) {
		if(!isset($name) || empty($name)) return false;
		$this->CookieBake($name, "", -1);
	}

	public function BurnAllCookies() {
		foreach($_COOKIE as $name => $props) {
			$this->CookieBurn($name);
		}
	}

	public static function StringHasValue($str = STR_EMP) {
		if(!isset($str) || empty($str) || is_null($str))
			return false;

		$str = str_replace(array(" ", "\t", "\r", "\n"), "", $str);
		return (strlen($str) > 0 && !empty($str) && !is_null($str));
	}

}
?>
