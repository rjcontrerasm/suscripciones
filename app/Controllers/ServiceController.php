<?php
namespace App\Controllers;
use App\Core\Auth; use App\Core\Controller; use App\Core\Database; use App\Models\Service;
class ServiceController extends Controller {
public function index(): void { Auth::requireRole(['Administrador','Operador','Solo lectura']); $f=['q'=>trim($_GET['q']??''),'estado'=>trim($_GET['estado']??'')]; $db=Database::connection(); $this->view('services/index',['services'=>(new Service())->all($f),'filters'=>$f,'clientes'=>$db->query('SELECT id, razon_social FROM clientes WHERE deleted_at IS NULL ORDER BY razon_social')->fetchAll(),'tipos'=>$db->query('SELECT id, nombre FROM tipos_servicio ORDER BY nombre')->fetchAll()]); }
public function create(): void { Auth::requireRole(['Administrador','Operador']); verify_csrf(); (new Service())->create($_POST); $this->redirect('/servicios'); }
public function update(): void { Auth::requireRole(['Administrador','Operador']); verify_csrf(); (new Service())->update((int)$_POST['id'],$_POST); $this->redirect('/servicios'); }
public function delete(): void { Auth::requireRole(['Administrador']); verify_csrf(); (new Service())->delete((int)$_POST['id']); $this->redirect('/servicios'); }
public function export(): void { Auth::requireRole(['Administrador','Operador','Solo lectura']); $rows=(new Service())->all(['q'=>trim($_GET['q']??''),'estado'=>trim($_GET['estado']??'')]); header('Content-Type:text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename=servicios.csv'); $o=fopen('php://output','w'); if($rows){fputcsv($o,array_keys($rows[0])); foreach($rows as $r){fputcsv($o,$r);} } fclose($o); exit; }
}
