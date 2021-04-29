<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-AllowHeaders, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../model/Mesin.php';
$database = new Database();
$db = $database->getConnection();
$mesin = new Mesin($db);
$request = $_SERVER['REQUEST_METHOD'];

switch ($request)
{
		case 'GET' :	
			if(!isset($_GET['id_mesin'])){
				$stmt = $mesin->read();
				$num = $stmt->rowCount();
				if($num>0){
					$array_mesin=array();
					$array_mesin["entri"]=array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						extract($row);
						$list_mesin=array(
						"id_mesin" 		=> $id_mesin,
						"nama_mesin" 	=> $nama_mesin,
						"status" 		=> $status
					);
					array_push($array_mesin["entri"], $list_mesin);
					}
				http_response_code(200);
				echo json_encode($array_mesin);
				}
					else{
					http_response_code(404);
					echo json_encode(array("message" => "Tidak ada entri mesin"));
				} 
			}
				elseif($_GET['id_mesin'] == NULL){
				echo json_encode(array("message" => "Parameter id id_mesin tidak boleh kosong"));
				}
					else{
						$mesin->id_mesin =$_GET['id_mesin'];
						$mesin->readOne();
						if($mesin->id_mesin!=null){
						$list_mesin=array(
						"id_mesin" 	=> $mesin->id_mesin,
						"nama_mesin" => $mesin->nama_mesin,
						"status" 	=> $mesin->status
						);
						http_response_code(200);
						echo json_encode($list_mesin);
					}
				else{
				http_response_code(404);
				echo json_encode(array("message" => "Mesin tidak ditemukan."));
				} 
			}
		break;
		case 'POST' :
			if(
				isset($_POST['nama_mesin'])&&
				isset($_POST['status'])
				){
				$mesin->nama_mesin 	= $_POST['nama_mesin'];
				$mesin->status 		= $_POST['status'];
					if($mesin->create()){
						http_response_code(201);
						echo json_encode(array("pesan_status" => "entri mesin telah ditambah."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Tidak dapat menambahkan entri mesin"
				);
				echo json_encode($result);
			}
		}
			else{
				http_response_code(400);
				$result=array(
				"status_kode" => 400,
				"pesan_status" => "Tidak dapat menambahkan entri mesin."
				);
				echo json_encode($result);
		}
			break;
			case 'PUT' :
				$data = json_decode(file_get_contents("php://input"));
				$id_mesin = $data->id_mesin;
					if($id_mesin==""|| $id_mesin==null){
						echo json_encode(array("message" => "Parameter id id_mesin tidak boleh kosong"));
				}
					else{
						$mesin->id_mesin 	= $data->id_mesin;
						$mesin->nama_mesin 	= $data->nama_mesin;
						$mesin->status 		= $data->status;
						if($mesin->update()){
							http_response_code(200);
							echo json_encode(array("message" => "entri mesin telah diperbaharui."));
					}
					else{
						http_response_code(503);
						$result=array(
						"status_kode" => 503,
						"pesan_status" => "Kesalahan Permintaan, Tidak dapat memperbaharui entri"
						);
						echo json_encode($result);
						echo json_encode(array("message" => "Tidak dapat memperbaharui entri mesin."));
				} 
			}
			break;
			case 'DELETE' :
				if(!isset($_GET['id_mesin'])){
					echo json_encode(array("message" => "Parameter id id_mesin tidak ada"));
				}
					elseif($_GET['id_mesin'] == NULL){
						echo json_encode(array("message" => "Parameter id id_mesin tidak boleh kosong"));
					}
				else{
					$mesin->id_mesin =$_GET['id_mesin'];
					if($mesin->delete()){
						http_response_code(200);
						echo json_encode(array("message" => "entri mesin sudah terhapus."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Kesalahan dalam permintaan, entri mesin tidak terinput"
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