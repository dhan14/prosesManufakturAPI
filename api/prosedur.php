<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-AllowHeaders, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../model/Prosedur.php';
$database = new Database();
$db = $database->getConnection();
$prosedur = new Prosedur($db);
$request = $_SERVER['REQUEST_METHOD'];

switch ($request)
{
		case 'GET' :	
			if(!isset($_GET['id_prosedur'])){
				$stmt = $prosedur->read();
				$num = $stmt->rowCount();
				if($num>0){
					$array_prosedur=array();
					$array_prosedur["entri"]=array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						extract($row);
						$list_prosedur=array(
						"id_prosedur" 	=> $id_prosedur,
						"nama_prosedur" => $nama_prosedur,
						"deskripsi" 	=> $deskripsi
					);
					array_push($array_prosedur["entri"], $list_prosedur);
					}
				http_response_code(200);
				echo json_encode($array_prosedur);
				}
					else{
					http_response_code(404);
					echo json_encode(array("message" => "Tidak ada Entri prosedur"));
				} 
			}
				elseif($_GET['id_prosedur'] == NULL){
				echo json_encode(array("message" => "Parameter id id_prosedur tidak boleh kosong"));
				}
					else{
						$prosedur->id_prosedur =$_GET['id_prosedur'];
						$prosedur->readOne();
						if($prosedur->id_prosedur!=null){
						$list_prosedur=array(
						"id_prosedur" 	=> $prosedur->id_prosedur,
						"nama_prosedur" => $prosedur->nama_prosedur,
						"deskripsi" 	=> $prosedur->deskripsi
						);
						http_response_code(200);
						echo json_encode($list_prosedur);
					}
				else{
				http_response_code(404);
				echo json_encode(array("message" => "Prosedur tidak ditemukan."));
				} 
			}
		break;
		case 'POST' :
			if(
				isset($_POST['nama_prosedur'])&&
				isset($_POST['deskripsi'])
				){
				$prosedur->nama_prosedur = $_POST['nama_prosedur'];
				$prosedur->deskripsi 		 = $_POST['deskripsi'];
					if($prosedur->create()){
						http_response_code(201);
						echo json_encode(array("pesan_status" => "Entri prosedur telah ditambah."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Tidak dapat menambahkan entri prosedur"
				);
				echo json_encode($result);
			}
		}
			else{
				http_response_code(400);
				$result=array(
				"status_kode" => 400,
				"pesan_status" => "Tidak dapat menambahkan entri prosedur."
				);
				echo json_encode($result);
		}
			break;
			case 'PUT' :
				$data = json_decode(file_get_contents("php://input"));
				$id_prosedur = $data->id_prosedur;
					if($id_prosedur==""|| $id_prosedur==null){
						echo json_encode(array("message" => "Parameter id id_prosedur tidak boleh kosong"));
				}
					else{
						$prosedur->id_prosedur 		= $data->id_prosedur;
						$prosedur->nama_prosedur 	= $data->nama_prosedur;
						$prosedur->deskripsi 		= $data->deskripsi;
						if($prosedur->update()){
							http_response_code(200);
							echo json_encode(array("message" => "Entri prosedur telah diperbaharui."));
					}
					else{
						http_response_code(503);
						$result=array(
						"status_kode" => 503,
						"pesan_status" => "Kesalahan Permintaan, Tidak dapat memperbaharui entri"
						);
						echo json_encode($result);
						echo json_encode(array("message" => "Tidak dapat memperbaharui entri prosedur."));
				} 
			}
			break;
			case 'DELETE' :
				if(!isset($_GET['id_prosedur'])){
					echo json_encode(array("message" => "Parameter id id_prosedur tidak ada"));
				}
					elseif($_GET['id_prosedur'] == NULL){
						echo json_encode(array("message" => "Parameter id id_prosedur tidak boleh kosong"));
					}
				else{
					$prosedur->id_prosedur =$_GET['id_prosedur'];
					if($prosedur->delete()){
						http_response_code(200);
						echo json_encode(array("message" => "Entri prosedur sudah terhapus."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Kesalahan dalam permintaan, Entri prosedur tidak dapat dihapus"
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