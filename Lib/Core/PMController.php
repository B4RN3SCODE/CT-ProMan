<?php
include_once(LIB_PATH . "Core/PMViewFactory.php");
/****************************************************
 * PMController
 * Baes class for the application controllers
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
class PMController {
	/*
	 * NOTE
	 * 		when we know more about what each module's controller
	 * 		requires to run... we can add tasks here (like
	 * 		pre_action, action, and post_action tasks) that execute
	 * 		when we need it.,.... for now this is a pretty simple
	 * 		process
	 */
	// module
	public $_module;
	// view
	public $_view;
	// action
	public $_action;
	// view processor object
	public $_viewProcessor;
	// service adapter for data manipulation
	public $_serviceAdapter;

	private $_hasAction;


	public function PMController(array $props_vals = array()) {

	}



	public function setVars(array $arr = array()) {
		foreach($arr as $prop => $val) {
			if(property_exists($this, $prop)) {
				$this->$prop = $val;
			}
		}
	}


	public function Init() {
	}

	public function Proc($forceIndexAction = false) {
	}


}
?>
