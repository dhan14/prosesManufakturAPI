<?php
class Prosedur{
private $conn;
private $table_name = "prosedur";
public $id_prosedur;
public $nama_prosedur;
public $deskripsi;

public function __construct($db){
	$this->conn = $db;
}

	function read(){
		$query = "SELECT id_prosedur, nama_prosedur,
		deskripsi 
		FROM " . $this->table_name . " 
		ORDER BY id_prosedur ASC";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function readOne(){
		$query = "SELECT 
		id_prosedur,
		nama_prosedur,
		deskripsi 
		FROM " . $this->table_name . " 
		WHERE id_prosedur = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_prosedur);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nama_prosedur = $row['nama_prosedur'];
		$this->deskripsi = $row['deskripsi'];
	}

	function create(){
		$query = "INSERT INTO
		" . $this->table_name . "
		SET
		nama_prosedur=:nama_prosedur,
		deskripsi=:deskripsi";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":nama_prosedur", $this->nama_prosedur);
		$stmt->bindParam(":deskripsi", $this->deskripsi);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
	function update(){
		$query = "UPDATE
		" . $this->table_name . "
		SET
		nama_prosedur=:nama_prosedur,
		deskripsi=:deskripsi
		WHERE
		id_prosedur = :id_prosedur";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":nama_prosedur", $this->nama_prosedur);
		$stmt->bindParam(":deskripsi", $this->deskripsi);
		$stmt->bindParam(':id_prosedur', $this->id_prosedur);
		if($stmt->execute()){
		return true;
			}
		return false;
	}
	function delete(){
		$query = "DELETE FROM "
		. $this->table_name ." WHERE id_prosedur = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_prosedur);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
}
?>