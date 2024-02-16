<?php
class Database {
	private $_db, $_count, $_lastID;

	public function __construct ($dbhost, $dbname, $dbusername, $dbpass) {
		try {
			$this->_db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8mb4;',$dbusername,$dbpass);
			$this->_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$this->_db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			echo 'Could not connect to DB';
			die;
		}
	}

	public function prepare($sql) {
		$stmt = $this->_db->prepare($sql);
		return $stmt;
	}

	public function setAttribute($attribute, $value) {
		$this->_db->setAttribute($attribute, $value);
	}

	public function insertRow ($sql, $values = []) {
		$stmt = $this->_db->prepare($sql);
		$stmt->execute($values);
		$this->_count = $stmt->rowCount();
		return $this->_count;
	}

	public function updateRow ($sql, $values = []) {
		$stmt = $this->_db->prepare($sql);
		$stmt->execute($values);
		$this->_count = $stmt->rowCount();
		return $this->_count;
	}

	public function deleteRow ($sql, $values = []) {
		$stmt = $this->_db->prepare($sql);
		$stmt->execute($values);
		$this->_count = $stmt->rowCount();
		return $this->_count;
	}

	public function getCount() {
		return $this->_count;
	}

	public function getRow($sql, $values = [], $fetchStyle = null) {
		$stmt = $this->_db->prepare($sql);
		$stmt->execute($values);
		return $stmt->fetch($fetchStyle);
	}

	public function getRows($sql, $values = [], $fetchStyle = null) {
		$stmt = $this->_db->prepare($sql);
		$stmt->execute($values);
		return $stmt->fetchAll($fetchStyle);
	}

	// memory friendly getRows
	public function selectRows($sql, $values = []) {
		$stmt = $this->_db->prepare($sql);
		$stmt->execute($values);
		return $stmt;
	}

	public function getLastID() {
		//	PHP manual: PDO::lastInsertId returns string
		return (int)$this->_db->lastInsertId();
	}

	public function getColumn($sql, $values = []) {
		$stmt = $this->_db->prepare($sql);
		$stmt->execute($values);
		return $stmt->fetchColumn();
	}

	public function beginTransaction() {
		$this->_db->beginTransaction();
	}

	public function commit() {
		$this->_db->commit();
	}

	public function rollBack() {
		$this->_db->rollBack();
	}
}
?>