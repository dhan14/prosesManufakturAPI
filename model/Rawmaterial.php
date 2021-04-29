<?php
class Rawmaterial{
private $conn;
private $table_name = "rawmaterial";
public $id_rm;
public $nama_mtrl;
public $stok;

public function __construct($db){
	$this->conn = $db;
}

	function read(){
		$query = "SELECT id_rm, nama_mtrl, stok 
		FROM " . $this->table_name . " 
		ORDER BY id_rm ASC";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function readOne(){
		$query = "SELECT 
		id_rm,
		nama_mtrl,
		stok 
		FROM " . $this->table_name . " 
		WHERE id_rm = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_rm);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nama_mtrl = $row['nama_mtrl'];
		$this->stok = $row['stok'];
	}

	function create(){
		$query = "INSERT INTO
		" . $this->table_name . "
		SET
		nama_mtrl=:nama_mtrl,
		stok=:stok";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":nama_mtrl", $this->nama_mtrl);
		$stmt->bindParam(":stok", $this->stok);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
	function update(){
		$query = "UPDATE
		" . $this->table_name . "
		SET
		nama_mtrl=:nama_mtrl,
		stok=:stok
		WHERE
		id_rm = :id_rm";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":nama_mtrl", $this->nama_mtrl);
		$stmt->bindParam(":stok", $this->stok);
		$stmt->bindParam(':id_rm', $this->id_rm);
		if($stmt->execute()){
		return true;
			}
		return false;
	}
	function delete(){
		$query = "DELETE FROM "
		. $this->table_name ." WHERE id_rm = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_rm);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
}
?>