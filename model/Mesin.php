<?php
class Mesin{
private $conn;
private $table_name = "mesin";
public $id_mesin;
public $nama_mesin;
public $status;

public function __construct($db){
	$this->conn = $db;
}

	function read(){
		$query = "SELECT id_mesin, nama_mesin,
		status 
		FROM " . $this->table_name . " 
		ORDER BY id_mesin ASC";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function readOne(){
		$query = "SELECT 
		id_mesin,
		nama_mesin,
		status 
		FROM " . $this->table_name . " 
		WHERE id_mesin = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_mesin);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nama_mesin = $row['nama_mesin'];
		$this->status = $row['status'];
	}

	function create(){
		$query = "INSERT INTO
		" . $this->table_name . "
		SET
		nama_mesin=:nama_mesin,
		status=:status";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":nama_mesin", $this->nama_mesin);
		$stmt->bindParam(":status", $this->status);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
	function update(){
		$query = "UPDATE
		" . $this->table_name . "
		SET
		nama_mesin=:nama_mesin,
		status=:status
		WHERE
		id_mesin = :id_mesin";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":nama_mesin", $this->nama_mesin);
		$stmt->bindParam(":status", $this->status);
		$stmt->bindParam(':id_mesin', $this->id_mesin);
		if($stmt->execute()){
		return true;
			}
		return false;
	}
	function delete(){
		$query = "DELETE FROM "
		. $this->table_name ." WHERE id_mesin = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_mesin);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
}
?>