<?php

class DBProcedure {

	// -- consts
	// --- data types
	const DP_INT = "int";
	const DP_VARCHAR = "varchar";
	const DP_TEXT = "text";
	const DP_DATE = "date";
	const DP_DATETIME = "datetime";
	const DP_BOOLEAN = "boolean";
	// --- params
	const DP_PARAM_IN = "IN";
	const DP_PARAM_OUT = "OUT";
	const DP_PARAM_IN_OUT = "in_out";
	const DP_PARAM_NAME = "name";
	const DP_PARAM_TYPE = "type";
	const DP_PARAM_SIZE = "size";
	// -- args

	private $name;
	private $content;
	private $params;

	// -- functions
	/**
	 *
	 */
	public function __construct($name, $content) {
		$this->name = $name;
		$this->content = $content;
		$this->params = array();
	}
	/**
	 *
	 */
	public function GetName() {
		return $this->name;
	}

	/**
	 *
	 */
	public function GetContent() {
		return $this->content;
	}

	/**
	 *
	 */
	public function GetParams() {
		return $this->params;
	}

	/**
	 *
	 */
	public function AddParam($inOut, $name, $type, $size) {
		array_push($params, array(
			DBProcedure::DP_PARAM_IN_OUT => $inOut,
			DBProcedure::DP_PARAM_NAME => $name,
			DBProcedure::DP_PARAM_TYPE => $type,
			DBProcedure::DP_PARAM_SIZE => $size
			));
		return $this;
	}

	public function getCREATEQuery($replace = true) {
		$query = "";
		if($replace) {
			$query .= $this->GetDROPQuery();
		}
		$query .= "CREATE PROCEDURE " . $this->name . "(";
		foreach($params as $param) {
			$query .= $param[DBProcedure::DP_PARAM_IN_OUT] . " " . $param[DBProcedure::DP_PARAM_NAME] . " " . $param[DBProcedure::DP_PARAM_TYPE] . ", ";
		}
		$query = trim($query, ",") . ") BEGIN " . $content . " END";
		return $query;
	}

	public function GetDROPQuery() {
		return "DROP PROCEDURE IF EXISTS " . $this->name .";";
	}

	public function GetCALLQuery() {
		$query = "CALL " . $this->name;
		return $query;
	}
}