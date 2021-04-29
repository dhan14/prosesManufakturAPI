<?php
class Karyawan{
private $conn;
private $table_name = "karyawan";
public $id_karyawan;
public $nip;
public $nama_karyawan;
public $operator;

public function __construct($db){
	$this->conn = $db;
}

	function read(){
			$query = "SELECT id_karyawan, 
			nip, 
			nama_karyawan, 
			operator 
			FROM " . $this->table_name . " 
			ORDER BY id_karyawan ASC";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;
	}

	function readOne(){
		$query = "SELECT 
		id_karyawan, 
		nip, 
		nama_karyawan, 
		operator 
		FROM " . $this->table_name . " 
		WHERE id_karyawan = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_karyawan);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nip = $row['nip'];
		$this->nama_karyawan = $row['nama_karyawan'];
		$this->operator = $row['operator'];
	}

	function create(){
		$query = "INSERT INTO
		" . $this->table_name . "
		SET
		nip=:nip,
		nama_karyawan=:nama_karyawan,
		operator=:operator";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":nip", $this->nip);
		$stmt->bindParam(":nama_karyawan", $this->nama_karyawan);
		$stmt->bindParam(":operator", $this->operator);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
	function update(){
		$query = "UPDATE
		" . $this->table_name . "
		SET
		nip=:nip,
		nama_karyawan=:nama_karyawan,
		operator=:operator
		WHERE
		id_karyawan = :id_karyawan";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":nip", $this->nip);
		$stmt->bindParam(":nama_karyawan", $this->nama_karyawan);
		$stmt->bindParam(":operator", $this->operator);
		$stmt->bindParam(':id_karyawan', $this->id_karyawan);
		if($stmt->execute()){
		return true;
			}
		return false;
	}
	function delete(){
		$query = "DELETE FROM "
		. $this->table_name ." WHERE id_karyawan = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_karyawan);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
}
?>