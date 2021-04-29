<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-AllowHeaders, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../model/Warehouse.php';
$database = new Database();
$db = $database->getConnection();
$warehouse = new Warehouse($db);
$request = $_SERVER['REQUEST_METHOD'];

switch ($request)
{
		case 'GET' : 	
			if(!isset($_GET['id_wh'])){
				$stmt = $warehouse->read();
				$num = $stmt->rowCount();
					if($num>0){
					$array_wh=array();
					$array_wh["Entri"]=array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						extract($row);
						$list_wh=array(
						"id_wh"				=> $id_wh,
						"nama_warehouse"	=> $nama_warehouse,
						"id_produk"			=> $id_produk,
						"nama_produk"		=> $nama_produk,
						"stok"				=> $stok,
						"deskripsi"			=> $deskripsi
					);
					array_push($array_wh["Entri"], $list_wh);
					}
				http_response_code(200);
				echo json_encode($array_wh);
				}
					else{
					http_response_code(404);
					echo json_encode(array("message" => "Tidak ada entri warehouse"));
				} 
			}
				elseif($_GET['id_wh'] == NULL){
				echo json_encode(array("message" => "Parameter id id_wh tidak boleh kosong"));
				}
					else{
						$warehouse->id_wh =$_GET['id_wh'];
						$warehouse->readOne();
						if($warehouse->id_wh!=null){
						$list_wh=array(
						"id_wh"				=> $warehouse->id_wh,
						"nama_warehouse"	=> $warehouse->nama_warehouse,
						"id_produk"			=> $warehouse->id_produk,
						"nama_produk"		=> $warehouse->nama_produk,
						"stok"				=> $warehouse->stok,
						"deskripsi"			=> $warehouse->deskripsi
						);
						http_response_code(200);
						echo json_encode($list_wh);
					}
				else{
				http_response_code(404);
				echo json_encode(array("message" => "Produk tidak ditemukan."));
				} 
			}
		break;
		case 'POST' :
			if(
				isset($_POST['id_produk'])&&
				isset($_POST['stok'])&&
				isset($_POST['deskripsi'])&&
				isset($_POST['nama_warehouse'])
				){
				$warehouse->id_produk 		= $_POST['id_produk'];
				$warehouse->stok 			= $_POST['stok'];
				$warehouse->deskripsi		= $_POST['deskripsi'];
				$warehouse->nama_warehouse	= $_POST['nama_warehouse'];
				
					if($warehouse->create()){
					http_response_code(201);
					echo json_encode(array("pesan_status" => "Entri warehouse telah ditambah."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Tidak dapat menambahkan Entri warehouse"
				);
				echo json_encode($result);
			}
		}
			else{
				http_response_code(400);
				$result=array(
				"status_kode" => 400,
				"pesan_status" => "Tidak dapat menambahkan Entri warehouse."
				);
				echo json_encode($result);
		}
			break;
			case 'PUT' :
				$data = json_decode(file_get_contents("php://input"));
				$id_wh = $data->id_wh;
					if($id_wh==""|| $id_wh==null){
					echo json_encode(array("message" => "Parameter id id_wh tidak boleh kosong"));
				}
					else{
						$warehouse->id_wh			= $data->id_wh;
						$warehouse->id_produk		= $data->id_produk;
						$warehouse->stok			= $data->stok;
						$warehouse->deskripsi		= $data->deskripsi;
						$warehouse->nama_warehouse	= $data->nama_warehouse;
						if($warehouse->update()){
							http_response_code(200);
							echo json_encode(array("message" => "Entri warehouse telah diperbaharui."));
					}
					else{
						http_response_code(503);
						$result=array(
						"status_kode" => 503,
						"pesan_status" => "Kesalahan Permintaan, Tidak dapat memperbaharui entri"
						);
						echo json_encode($result);
						echo json_encode(array("message" => "Tidak dapat memperbaharui Entri warehouse."));
				} 
			}
			break;
			case 'DELETE' :
				if(!isset($_GET['id_wh'])){
					echo json_encode(array("message" => "Parameter id id_wh tidak ada"));
				}
					elseif($_GET['id_wh'] == NULL){
						echo json_encode(array("message" => "Parameter id id_wh tidak boleh kosong"));
					}
				else{
				$warehouse->id_wh =$_GET['id_wh'];
				if($warehouse->delete()){
					http_response_code(200);
					echo json_encode(array("message" => "Entri warehouse sudah terhapus."));
				}
				else{
					http_response_code(503);
					$result=array(
					"status_kode" => 503,
					"pesan_status" => "Kesalahan dalam permintaan, Entri warehouse tidak terinput"
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