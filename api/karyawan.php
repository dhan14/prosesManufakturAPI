<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-AllowHeaders, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../model/Karyawan.php';
$database = new Database();
$db = $database->getConnection();
$karyawan = new Karyawan($db);
$request = $_SERVER['REQUEST_METHOD'];

switch ($request)
{
		case 'GET' :	
			if(!isset($_GET['id_karyawan'])){
				$stmt = $karyawan->read();
				$num = $stmt->rowCount();
					if($num>0){
					$array_karyawan=array();
					$array_karyawan["entri"]=array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						extract($row);
						$list_karyawan=array(
						"id_karyawan" 	=> $id_karyawan,
						"nip" 			=> $nip,
						"nama_karyawan" => $nama_karyawan,
						"operator" 		=> $operator
					);
					array_push($array_karyawan["entri"], $list_karyawan);
					}
				http_response_code(200);
				echo json_encode($array_karyawan);
				}
					else{
					http_response_code(404);
					echo json_encode(array("message" => "Tidak ada entri karyawan"));
				} 
			}
				elseif($_GET['id_karyawan'] == NULL){
				echo json_encode(array("message" => "Parameter Id id_karyawan tidak boleh kosong"));
				}
					else{
						$karyawan->id_karyawan =$_GET['id_karyawan'];
						$karyawan->readOne();
						if($karyawan->id_karyawan!=null){
						$list_karyawan=array(
						"id_karyawan" 	=> $karyawan->id_karyawan,
						"nip" 			=> $karyawan->nip,
						"nama_karyawan" => $karyawan->nama_karyawan,
						"operator" 		=> $karyawan->operator
						);
						http_response_code(200);
						echo json_encode($list_karyawan);
					}
				else{
				http_response_code(404);
				echo json_encode(array("message" => "Karyawan tidak ditemukan."));
				} 
			}
		break;
		case 'POST' :
			if(
				isset($_POST['nip'])&&
				isset($_POST['nama_karyawan'])&&
				isset($_POST['operator'])
				){
				$karyawan->nip 				= $_POST['nip'];
				$karyawan->nama_karyawan 	= $_POST['nama_karyawan'];
				$karyawan->operator 		= $_POST['operator'];
					if($karyawan->create()){
					http_response_code(201);
					echo json_encode(array("pesan_status" => "Entri karyawan telah ditambah."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Tidak dapat menambahkan entri karyawan"
				);
				echo json_encode($result);
			}
		}
			else{
				http_response_code(400);
				$result=array(
				"status_kode" => 400,
				"pesan_status" => "Tidak dapat menambahkan entri karyawan."
				);
				echo json_encode($result);
		}
			break;
			case 'PUT' :
				$data = json_decode(file_get_contents("php://input"));
				$id_karyawan = $data->id_karyawan;
					if($id_karyawan==""|| $id_karyawan==null){
					echo json_encode(array("message" => "Parameter Id id_karyawan tidak boleh kosong"));
				}
					else{
						$karyawan->id_karyawan 		= $data->id_karyawan;
						$karyawan->nip 				= $data->nip;
						$karyawan->nama_karyawan 	= $data->nama_karyawan;
						$karyawan->operator 		= $data->operator;
						if($karyawan->update()){
							http_response_code(200);
							echo json_encode(array("message" => "Entri karyawan telah diperbaharui."));
					}
					else{
						http_response_code(503);
						$result=array(
						"status_kode" => 503,
						"pesan_status" => "Kesalahan Permintaan, Tidak dapat memperbaharui entri"
						);
						echo json_encode($result);
						echo json_encode(array("message" => "Tidak dapat memperbaharui entri karyawan."));
				} 
			}
			break;
			case 'DELETE' :
				if(!isset($_GET['id_karyawan'])){
					echo json_encode(array("message" => "Parameter Id id_karyawan tidak ada"));
				}
					elseif($_GET['id_karyawan'] == NULL){
						echo json_encode(array("message" => "Parameter Id id_karyawan tidak boleh kosong"));
					}
				else{
				$karyawan->id_karyawan =$_GET['id_karyawan'];
				if($karyawan->delete()){
					http_response_code(200);
					echo json_encode(array("message" => "Entri karyawan sudah terhapus."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Kesalahan dalam permintaan, entri karyawan tidak terinput"
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