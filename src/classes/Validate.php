<?php
class Validate {

	public $errors = [];

	public function getErrors() {
		return ($this->errors);
	}

	public function SetErrors() {
		$this->errors['Email'] = 'Email not sent!';
	}

	public function SetExpectedErrors($input,$msg) {
		$this->errors[$input] = $msg;
	}

	public function SetPresenceError() {
		$this->errors['Presence'] = true;
	}

	/*
	* extended trim (accepts array as argument and recursively trims it's elements)
	* @param mixed $var
	*/
	public function trim_e($var) {
		if (is_array($var)) {
			foreach ($var as $k => $v) {
				if (is_array($v)) {
					$var[$k] = trim_e($v);
				} else {
					$var[$k] = trim($v);
				}
			}
			return $var;
		} else {
			$var = trim($var);
		}
		return $var;
	}

	public function CheckRequiredArray($array) {
		foreach ($array as $k =>$v) {
			if (empty($array[$k])) {
				$this->errors[$k] = 'Required';
			}
		}
	}

	public function checkRequired($var,$InputFieldName) {
		if ($var === '') {
			$this->errors["{$InputFieldName}"] = 'Required';
		}
	}

	public function checkName($var,$InputFieldName) {
		if ($var !== '' && !preg_match('~\A[\p{L}]+[\p{L} ]*[\p{L}]*\Z~u', $var)) {
			$this->errors["{$InputFieldName}"] = 'Letters only';
		}
	}

	public function checkEmail($var,$InputFieldName) {
		if ($var !== '' && !filter_var($var, FILTER_VALIDATE_EMAIL)) {
			$this->errors["{$InputFieldName}"] = 'Invalid E-mail format';
		}
	}

	public function checkEmailAsYouType($var) {
		if (filter_var($var, FILTER_VALIDATE_EMAIL)) {
			return(true);
		} else {
			return(false);
		}
	}

	public function checkAddress($var,$InputFieldName) {
		if(!empty($var) && (!preg_match('~\A[a-zA-Z\d]{1}[a-zA-Z (0-9)\-/\\.,&]{0,60}\Z~', $var))) {
			$this->errors["{$InputFieldName}"] = 'Error';
		}
	}

	public function checkPhone($var,$InputFieldName) {
		if (!empty($var) && (!preg_match('~\A[+]?[( ]?([0-9][ \-()]*){6,48}\Z~', $var))) {
			$this->errors["{$InputFieldName}"] = 'Invalid phone format';
		} elseif (strlen($var) > 50) {
			$this->errors["{$InputFieldName}"] = 'Maximum 50 chars';
		}
	}

	public function checkNumber($var,$min,$max,$InputFieldName) {
		if (!empty($var) && (!preg_match('~\A\d{' . $min . ',' . $max . '}\Z~', $var))) {
			$this->errors["{$InputFieldName}"] = '1-3 digits'; //validation in users index.php has ($Weight,1,3,'Weight') as option
		}
	}

	public function check_Date($var,$InputFieldName) {
		if ($var !== '' && !preg_match('~\A\d{4}-\d{2}-\d{2}\Z~', $var)) {
			$this->errors["{$InputFieldName}"] = 'Invalid date format';
		}
	}

	public function checkRegex ($var,$InputFieldName,$regex) {
		if (!preg_match($regex,$var)) {
			$this->errors["{$InputFieldName}"] = 'Error';
		}
	}

	public function checkUniRegex ($var,$InputFieldName) {
		if (!preg_match('~\A[a-zA-Z \d\-.,/\\)(]{0,60}\Z~',$var)) {
			$this->errors["{$InputFieldName}"] = 'Error';
		}
	}
}
?>