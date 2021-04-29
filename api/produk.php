<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-AllowHeaders, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../model/Produk.php';
$database = new Database();
$db = $database->getConnection();
$produk = new Produk($db);
$request = $_SERVER['REQUEST_METHOD'];

switch ($request)
{
		case 'GET' : 	
			if(!isset($_GET['id_produk'])){
				$stmt = $produk->read();
				$num = $stmt->rowCount();
					if($num>0){
					$array_produk=array();
					$array_produk["Entri"]=array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						extract($row);
						$list_produk=array(
						"id_produk"		=> $id_produk,
						"nama_produk"	=> $nama_produk,
						"id_rm"			=> $id_rm,
						"nama_mtrl"		=> $nama_mtrl,
						"id_prosedur"	=> $id_prosedur,
						"nama_prosedur"	=> $nama_prosedur,
						"id_mesin"		=> $id_mesin,
						"nama_mesin"	=> $nama_mesin,
						"id_karyawan"	=> $id_karyawan,
						"nama_karyawan"	=> $nama_karyawan
					);
					array_push($array_produk["Entri"], $list_produk);
					}
				http_response_code(200);
				echo json_encode($array_produk);
				}
					else{
					http_response_code(404);
					echo json_encode(array("message" => "Tidak ada entri produk"));
				} 
			}
				elseif($_GET['id_produk'] == NULL){
				echo json_encode(array("message" => "Parameter id id_produk tidak boleh kosong"));
				}
					else{
						$produk->id_produk =$_GET['id_produk'];
						$produk->readOne();
						if($produk->id_produk!=null){
						$list_produk=array(
						"id_produk"		=> $produk->id_produk,
						"nama_produk"	=> $produk->nama_produk,
						"id_rm"			=> $produk->id_rm,
						"nama_mtrl"		=> $produk->nama_mtrl,
						"id_prosedur"	=> $produk->id_prosedur,
						"nama_prosedur"	=> $produk->nama_prosedur,
						"id_mesin"		=> $produk->id_mesin,
						"nama_mesin"	=> $produk->nama_mesin,
						"id_karyawan"	=> $produk->id_karyawan,
						"nama_karyawan"	=> $produk->nama_karyawan
						);
						http_response_code(200);
						echo json_encode($list_produk);
					}
				else{
				http_response_code(404);
				echo json_encode(array("message" => "Produk tidak ditemukan."));
				} 
			}
		break;
		case 'POST' :
			if(
				isset($_POST['id_rm'])&&
				isset($_POST['id_prosedur'])&&
				isset($_POST['id_mesin'])&&
				isset($_POST['id_karyawan'])&&
				isset($_POST['nama_produk'])
				){
				$produk->id_rm 		= $_POST['id_rm'];
				$produk->id_prosedur= $_POST['id_prosedur'];
				$produk->id_mesin	= $_POST['id_mesin'];
				$produk->id_karyawan= $_POST['id_karyawan'];
				$produk->nama_produk= $_POST['nama_produk'];
					if($produk->create()){
					http_response_code(201);
					echo json_encode(array("pesan_status" => "Entri produk telah ditambah."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Tidak dapat menambahkan entri produk"
				);
				echo json_encode($result);
			}
		}
			else{
				http_response_code(400);
				$result=array(
				"status_kode" => 400,
				"pesan_status" => "Tidak dapat menambahkan entri produk."
				);
				echo json_encode($result);
		}
			break;
			case 'PUT' :
				$data = json_decode(file_get_contents("php://input"));
				$id_produk = $data->id_produk;
					if($id_produk==""|| $id_produk==null){
					echo json_encode(array("message" => "Parameter id id_produk tidak boleh kosong"));
				}
					else{
						$produk->id_produk		= $data->id_produk;
						$produk->id_rm			= $data->id_rm;
						$produk->id_prosedur	= $data->id_prosedur;
						$produk->id_mesin		= $data->id_mesin;
						$produk->id_karyawan	= $data->id_karyawan;
						$produk->nama_produk	= $data->nama_produk;
						if($produk->update()){
							http_response_code(200);
							echo json_encode(array("message" => "Entri produk telah diperbaharui."));
					}
					else{
						http_response_code(503);
						$result=array(
						"status_kode" => 503,
						"pesan_status" => "Kesalahan Permintaan, Tidak dapat memperbaharui entri"
						);
						echo json_encode($result);
						echo json_encode(array("message" => "Tidak dapat memperbaharui entri produk."));
				} 
			}
			break;
			case 'DELETE' :
				if(!isset($_GET['id_produk'])){
					echo json_encode(array("message" => "Parameter id id_produk tidak ada"));
				}
					elseif($_GET['id_produk'] == NULL){
						echo json_encode(array("message" => "Parameter id id_produk tidak boleh kosong"));
					}
				else{
				$produk->id_produk =$_GET['id_produk'];
				if($produk->delete()){
					http_response_code(200);
					echo json_encode(array("message" => "Entri produk sudah terhapus."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Kesalahan dalam permintaan, entri produk tidak terinput"
					);
					echo json_encode($result);
					echo json_encode(array("message" => "Tidak dapat menghapus entri tersebut."));
				} 
			}
			break;
		default :
		http_response_code(404);
		echo "Request tidak diizinkan";
	}
?>