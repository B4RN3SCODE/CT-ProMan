<?php
/******************************************
 * DBCON
 * Main class for database connections;
 * primarily a MySQL(I) db class
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


class DBCon {

    /**		Properties		**/

    // hostname
    private $HOST;

    // database user to access as
    private $USER;

    // password for the user
    private $PWD;

    // database name
    private $DB;

    //table name
    private $TBL;

    // link reference
    private $LinkRef;

    // query statement
    private $QueryStmt;

    // query result
    private	$QueryRslt;

    // error message
    private $Error;

    // tracks the status for linked reference
    private $IsLinked;


    /**		End Properties		**/


    /************************************
     * Constructor
     ************************************/
    public function DBCon($host = DB_HOST, $user = DB_USER, $password = DB_PASS, $dbname = DB_NAME, $tablename = null, $qstmt = null) {
        $this->HOST = $host;
        $this->USER = $user;
        $this->PWD = $password;
        $this->DB = $dbname;
        $this->TBL = $tablename;
        $this->LinkRef = null;
        $this->QueryStmt = $qstmt;
        $this->QueryRslt = null;
        $this->Error = "";
        $this->IsLinked = false;
    }



    /**************************************
     * Creates a connection reference
     *
     * @return	boolean true for succeess
     *************************************/
    public function Link() {

        // if already linked, return
        if($this->getIsLinked()) return true;

        $this->LinkRef = null;
        $this->Error = "";
        $this->LinkRef = new mysqli($this->HOST, $this->USER, $this->PWD, $this->DB);

        if($this->LinkRef->connect_errno) {

            $this->LinkRef = null;
            $this->Error = "Could not connect";
            $this->setIsLinked(false);

        } else $this->setIsLinked(true);

        return $this->getIsLinked();
    }



    /********************************
     * Unsets all object propertys &
     * values
     ********************************/
    public function Kill() {

        foreach(get_class_vars("DBCon") as $prop => $val)
            unset($this->$prop);

    }


    /**		ACCESSORS		**/

    // get property values
    public function getHOST() { return $this->HOST; }
    public function getUSER() { return $this->USER; }
    public function getPWD() { return $this->PWD; }
    public function getDB() { return $this->DB; }
    public function getTBL() { return $this->TBL; }
    public function getLinkRef() { return $this->LinkRef; }
    public function getQueryStmt() { return $this->QueryStmt; }
    public function getQueryRslt() { return $this->QueryRslt; }
    public function getError() { return $this->Error; }
    public function getIsLinked() { return $this->IsLinked; }

    // set property values
    public function setTBL($tbl = null) {
        if(!isset($tbl) || strlen($tbl) < 1)
            return false;
        else $this->TBL = $tbl;
    }

    private function setLinkRef($ref = null) {
        $this->LinkRef = $ref;
    }

    public function setQueryStmt($stmt = null) {
        if(!isset($stmt) || strlen($stmt) < 1)
            return false;
        else $this->QueryStmt = $stmt;
    }

    public function setQueryRslt($rslt = null) {
        $this->QueryRslt = $rslt;
    }

    private function setError($err = null) {
        $this->Error = $err;
    }

    private function setIsLinked($bool = false) {
        $this->IsLinked = $bool;
    }



    /**			END ACCESSORS		**/


    /****************************
     * select a databse
     *****************************/
    public function SelectDb($db) {
        if($this->LinkRef->select_db($db))
            return true;

        return false;
    }


    /*********************************
     * Resets the query info
     ********************************/
    public function ResetQueryAndResult() {
        $this->QueryStmt = "";
        $this->QueryRslt = null;
    }

    public function GetAffectedRows() {
        if(!isset($this->LinkRef))
            return false;
        return $this->LinkRef->affected_rows;
    }

    /******************************
     * Fetches all query result
     * data
     *
     * @return	assoc array of data
     *********************************/
    public function GetAll() {
        if(!isset($this->QueryRslt))
            return false;

        $dat = array();
        while($tmp = $this->QueryRslt->fetch_array(MYSQLI_ASSOC)) {
            $dat[] = $tmp;
        }
        if(count($dat) > 0)
            return $dat;

        return array();
    }


    /*************************************
     * Fetches a row of query result data
     *
     * @return an assoc array of data
     *************************************/
    public function GetRow() {
        if($row = $this->QueryRslt->fetch_assoc())
            return $row;
        return false;
    }


    /********************************
     * Executes a query
     *
     * @return boolean true for success
     ***********************************/
    public function Query() {

        if($sent = $this->LinkRef->query($this->QueryStmt)) {
            $this->setQueryRslt($sent);
            return true;
        }
        $this->Error = "Query Failure: -- {$this->QueryStmt} --";
        return false;

    }

    /*****************************
     * Gets last inserted Id
     ****************************/
    public function GetLastInsertedId() {
        return $this->LinkRef->insert_id;
    }


    /*******************************
     * Gets Last Error Msg
     *******************************/
    public function GetLastErrorMsg() {
        return $this->LinkRef->error;
    }


    /***************************
     * Escapes the current query
     * String
     ***************************/
    public function EscapeQueryStmt($stmt = "") {
        $sqlStmt = stripslashes($stmt);
        $sqlStmt = $this->LinkRef->real_escape_string($sqlStmt);
        return $sqlStmt;
    }


    public function EscapeQuotes($stmt = "") {
		$sqlStmt = str_replace(array("'","\\'"), "''", $stmt);
		return $sqlStmt;
	}


    /****************************
     * Gets num rows
     *****************************/
    public function NumRows() {
        return $this->QueryRslt->num_rows;
    }


    /***************************************
     * Gets Columns Names of a Table
     ****************************************/
    public function Cols() {
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='{$this->DB}' AND TABLE_NAME='{$this->TBL}'";
        $this->setQueryStmt($sql);
        $this->Query();
        return $this->GetAll();
    }


}
?>
