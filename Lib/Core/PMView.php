<?php
/***************************************************
 * PMView
 *
 * Renders basic HTML shit
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
class PMView {

	public $_pageTitle;

	public $_cssHrefs = array();

	public $_jsSrcs = array();

	public $_metaTags = array();

	public $_viewTpl;

	public $_htmlHead;

	public $_tplData;

	public $_displayOptions = array();

	public static $DEFAULT_OPTIONS = array("head" => true, "nav" => true, "foot" => true);


	public function PMView() {
		$this->_pageTitle = STR_EMP;
		$this->_cssHrefs = array();
		$this->_jsSrcs = array();
		$this->_metaTags = array();
		$this->_htmlHead = "";
		$this->_tplData = "";
		$this->_displayOptions = self::$DEFAULT_OPTIONS;

	}

	public function ViewExists($pth = STR_EMP) {
		if(ProMan::StringHasValue($pth)) {
			return file_exists($pth);
		}
		return file_exists($this->_viewTpl);
	}

	public function BuildHead() {
		if(!is_null($this->_metaTags) && !empty($this->_metaTags) && count($this->_metaTags) > 0) {
			foreach($this->_metaTags as $meta)
				$this->_htmlHead .= "<meta property=\"{$meta["property"]}\" content=\"{$meta["content"]}\" />";
		}
		if(!is_null($this->_jsSrcs) && !empty($this->_jsSrcs) && count($this->_jsSrcs) > 0) {
			foreach($this->_jsSrcs as $src)
				$this->_htmlHead .= "<script type=\"text/javascript\" src=\"{$src}\"></script>\n";
		}
		if(!is_null($this->_cssHrefs) && !empty($this->_cssHrefs) && count($this->_cssHrefs) > 0) {
			foreach($this->_cssHrefs as $href)
				$this->_htmlHead .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"${href}\">";
		}
	}

	public function SetUp() {
		return true;
	}

	public function LoadView($listData = null) {
	}


	public function setOptions($opts = array()) {
		if(count($opts) < 1)
			$this->_displayOptions = self::$DEFAULT_OPTIONS;
		else {
			foreach($opts as $key => $val) {
				if(!array_key_exists($key, self::$DEFAULT_OPTIONS))
					continue;

				$this->_displayOptions[$key] = $val;
			}
		}
		return (count($this->_displayOptions) == count(self::$DEFAULT_OPTIONS));
	}

}
?>
