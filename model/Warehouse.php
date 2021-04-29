 <?php
class Warehouse{
private $conn;
private $table_name = "warehouse";
public $id_wh;
public $nama_warehouse;
public $id_produk;
public $nama_produk;
public $stok;
public $deskripsi;

public function __construct($db){
	$this->conn = $db;
}

	function read(){
			$query = "SELECT 
			warehouse.id_wh, 
			warehouse.nama_warehouse, 
			warehouse.id_produk, 
			produk.nama_produk, 
			warehouse.stok, 
			warehouse.deskripsi 
			FROM " . $this->table_name . "
			INNER JOIN produk 
				ON produk.id_produk = warehouse.id_produk
			ORDER BY id_wh ASC";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;
	}

	function readOne(){
		$query = "SELECT 
		warehouse.id_wh, 
		warehouse.nama_warehouse, 
		warehouse.id_produk, 
		produk.nama_produk, 
		warehouse.stok, 
		warehouse.deskripsi
		FROM " . $this->table_name . "
		INNER JOIN produk 
			ON produk.id_produk = warehouse.id_produk
		WHERE id_wh  = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_wh);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nama_warehouse= $row['nama_warehouse'];
		$this->id_produk	= $row['id_produk'];
		$this->nama_produk 	= $row['nama_produk'];
		$this->stok 		= $row['stok'];
		$this->deskripsi 	= $row['deskripsi'];
	}

	function create(){
		$query = "INSERT INTO
		" . $this->table_name . "
		SET
		id_produk=:id_produk,
		stok=:stok,
		deskripsi=:deskripsi,
		nama_warehouse=:nama_warehouse";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam("id_produk", $this->id_produk);
		$stmt->bindParam("stok", $this->stok);
		$stmt->bindParam("deskripsi", $this->deskripsi);
		$stmt->bindParam("nama_warehouse", $this->nama_warehouse);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
	function update(){
		$query = "UPDATE
		" . $this->table_name . "
		SET
		id_produk=:id_produk,
		stok=:stok,
		deskripsi=:deskripsi,
		nama_warehouse=:nama_warehouse
		WHERE
		id_wh = :id_wh";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam("id_produk", $this->id_produk);
		$stmt->bindParam("stok", $this->stok);
		$stmt->bindParam("deskripsi", $this->deskripsi);
		$stmt->bindParam("nama_warehouse", $this->nama_warehouse);
		$stmt->bindParam("id_wh", $this->id_wh);
		if($stmt->execute()){
		return true;
			}
		return false;
	}
	function delete(){
		$query = "DELETE FROM "
		. $this->table_name ." WHERE id_wh = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_wh);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
}
?>