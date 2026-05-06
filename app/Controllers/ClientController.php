<?php
namespace App\Controllers;
use App\Core\Auth; use App\Core\Controller; use App\Models\Client;
class ClientController extends Controller {
public function index(): void { Auth::requireRole(['Administrador','Operador','Solo lectura']); $m=new Client(); $q=trim($_GET['q']??''); $this->view('clients/index',['clients'=>$m->all($q),'search'=>$q]); }
public function create(): void { Auth::requireRole(['Administrador','Operador']); verify_csrf(); (new Client())->create($_POST); $this->redirect('/clientes'); }
public function update(): void { Auth::requireRole(['Administrador','Operador']); verify_csrf(); (new Client())->update((int)$_POST['id'],$_POST); $this->redirect('/clientes'); }
public function delete(): void { Auth::requireRole(['Administrador']); verify_csrf(); (new Client())->delete((int)$_POST['id']); $this->redirect('/clientes'); }
public function export(): void { Auth::requireRole(['Administrador','Operador','Solo lectura']); $rows=(new Client())->all(trim($_GET['q']??'')); header('Content-Type:text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename=clientes.csv'); $o=fopen('php://output','w'); if($rows){fputcsv($o,array_keys($rows[0])); foreach($rows as $r){fputcsv($o,$r);} } fclose($o); exit; }
}
