<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-AllowHeaders, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../model/Rawmaterial.php';
$database = new Database();
$db = $database->getConnection();
$raw_mat = new Rawmaterial($db);
$request = $_SERVER['REQUEST_METHOD'];

switch ($request)
{
		case 'GET' :	
			if(!isset($_GET['id_rm'])){
				$stmt = $raw_mat->read();
				$num = $stmt->rowCount();
				if($num>0){
					$array_rm=array();
					$array_rm["entri"]=array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						extract($row);
						$list_rm=array(
						"id_rm" 		=> $id_rm,
						"nama_mtrl" 	=> $nama_mtrl,
						"stok" 		=> $stok
					);
					array_push($array_rm["entri"], $list_rm);
					}
				http_response_code(200);
				echo json_encode($array_rm);
				}
					else{
					http_response_code(404);
					echo json_encode(array("message" => "Tidak ada Entri Raw Material"));
				} 
			}
				elseif($_GET['id_rm'] == NULL){
				echo json_encode(array("message" => "Parameter id id_rm tidak boleh kosong"));
				}
					else{
						$raw_mat->id_rm =$_GET['id_rm'];
						$raw_mat->readOne();
						if($raw_mat->id_rm!=null){
						$list_rm=array(
						"id_rm" 	=> $raw_mat->id_rm,
						"nama_mtrl" => $raw_mat->nama_mtrl,
						"stok" 	=> $raw_mat->stok
						);
						http_response_code(200);
						echo json_encode($list_rm);
					}
				else{
				http_response_code(404);
				echo json_encode(array("message" => "Mesin tidak ditemukan."));
				} 
			}
		break;
		case 'POST' :
			if(
				isset($_POST['nama_mtrl'])&&
				isset($_POST['stok'])
				){
				$raw_mat->nama_mtrl 	= $_POST['nama_mtrl'];
				$raw_mat->stok 		= $_POST['stok'];
					if($raw_mat->create()){
						http_response_code(201);
						echo json_encode(array("pesan_status" => "Entri Raw Material telah ditambah."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Tidak dapat menambahkan Entri Raw Material"
				);
				echo json_encode($result);
			}
		}
			else{
				http_response_code(400);
				$result=array(
				"status_kode" => 400,
				"pesan_status" => "Tidak dapat menambahkan Entri Raw Material."
				);
				echo json_encode($result);
		}
			break;
			case 'PUT' :
				$data = json_decode(file_get_contents("php://input"));
				$id_rm = $data->id_rm;
					if($id_rm==""|| $id_rm==null){
						echo json_encode(array("message" => "Parameter id id_rm tidak boleh kosong"));
				}
					else{
						$raw_mat->id_rm 	= $data->id_rm;
						$raw_mat->nama_mtrl 	= $data->nama_mtrl;
						$raw_mat->stok 		= $data->stok;
						if($raw_mat->update()){
							http_response_code(200);
							echo json_encode(array("message" => "Entri Raw Material telah diperbaharui."));
					}
					else{
						http_response_code(503);
						$result=array(
						"status_kode" => 503,
						"pesan_status" => "Kesalahan Permintaan, Tidak dapat memperbaharui entri"
						);
						echo json_encode($result);
						echo json_encode(array("message" => "Tidak dapat memperbaharui Entri Raw Material."));
				} 
			}
			break;
			case 'DELETE' :
				if(!isset($_GET['id_rm'])){
					echo json_encode(array("message" => "Parameter id id_rm tidak ada"));
				}
					elseif($_GET['id_rm'] == NULL){
						echo json_encode(array("message" => "Parameter id id_rm tidak boleh kosong"));
					}
				else{
					$raw_mat->id_rm =$_GET['id_rm'];
					if($raw_mat->delete()){
						http_response_code(200);
						echo json_encode(array("message" => "Entri Raw Material sudah terhapus."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Kesalahan dalam permintaan, Entri Raw Material tidak terinput"
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