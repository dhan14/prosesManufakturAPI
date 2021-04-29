 <?php
class Produk{
private $conn;
private $table_name = "produk";
public $id_produk;
public $nama_produk;
public $id_rm;
public $nama_mtrl;
public $id_prosedur;
public $nama_prosedur;
public $id_mesin;
public $id_karyawan;
public $nama_karyawan;

public function __construct($db){
	$this->conn = $db;
}

	function read(){
			$query = "SELECT 
			produk.id_produk, 
			produk.nama_produk,
			produk.id_rm, 
			rawmaterial.nama_mtrl, 
			produk.id_prosedur, 
			prosedur.nama_prosedur, 
			produk.id_mesin, 
			mesin.nama_mesin, 
			produk.id_karyawan, 
			karyawan.nama_karyawan 
			FROM " . $this->table_name . "
			INNER JOIN rawmaterial 
				ON produk.id_rm=rawmaterial.id_rm
			INNER JOIN prosedur 
				ON produk.id_prosedur=prosedur.id_prosedur
			INNER JOIN mesin 
				ON produk.id_mesin=mesin.id_mesin
			INNER JOIN karyawan 
				ON produk.id_karyawan=karyawan.id_karyawan
			ORDER BY id_produk ASC";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;
	}

	function readOne(){
		$query = "SELECT 
		produk.id_produk,
	produk.nama_produk,
	produk.id_rm,
	rawmaterial.nama_mtrl,
	produk.id_prosedur,
	prosedur.nama_prosedur,
	produk.id_mesin,
	mesin.nama_mesin,
	produk.id_karyawan,
	karyawan.nama_karyawan 
		FROM " . $this->table_name . "
		INNER JOIN rawmaterial 
			ON produk.id_rm=rawmaterial.id_rm
		INNER JOIN prosedur 
			ON produk.id_prosedur=prosedur.id_prosedur
		INNER JOIN mesin 
			ON produk.id_mesin=mesin.id_mesin
		INNER JOIN karyawan 
			ON produk.id_karyawan=karyawan.id_karyawan 
		WHERE id_produk = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_produk);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nama_produk 	= $row['nama_produk'];
		$this->id_rm 		= $row['id_rm'];
		$this->nama_mtrl 	= $row['nama_mtrl'];
		$this->id_prosedur 	= $row['id_prosedur'];
		$this->nama_prosedur= $row['nama_prosedur'];
		$this->id_mesin 	= $row['id_mesin'];
		$this->nama_mesin 	= $row['nama_mesin'];
		$this->id_karyawan 	= $row['id_karyawan'];
		$this->nama_karyawan= $row['nama_karyawan'];
	}

	function create(){
		$query = "INSERT INTO
		" . $this->table_name . "
		SET
		id_rm=:id_rm,
		id_prosedur=:id_prosedur,
		id_mesin=:id_mesin,
		id_karyawan=:id_karyawan,
		nama_produk=:nama_produk";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam("id_rm", $this->id_rm);
		$stmt->bindParam("id_prosedur", $this->id_prosedur);
		$stmt->bindParam("id_mesin", $this->id_mesin);
		$stmt->bindParam("id_karyawan", $this->id_karyawan);
		$stmt->bindParam("nama_produk", $this->nama_produk);
		if($stmt->execute()){
		return true;
		}
		return false;
	}
	function update(){
		$query = "UPDATE
		" . $this->table_name . "
		SET
		id_rm=:id_rm,
		id_prosedur=:id_prosedur,
		id_mesin=:id_mesin,
		id_karyawan=:id_karyawan,
		nama_produk=:nama_produk
		WHERE
		id_produk = :id_produk";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam("id_rm", $this->id_rm);
		$stmt->bindParam("id_prosedur", $this->id_prosedur);
		$stmt->bindParam("id_mesin", $this->id_mesin);
		$stmt->bindParam("id_karyawan", $this->id_karyawan);
		$stmt->bindParam("nama_produk", $this->nama_produk);
		$stmt->bindParam("id_produk", $this->id_produk);
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