<?php
namespace App\Controllers;
use App\Core\Auth; use App\Core\Controller; use App\Core\Database; use App\Models\Renewal;
class RenewalController extends Controller {
public function index(): void { Auth::requireRole(['Administrador','Operador','Solo lectura']); $db=Database::connection(); $this->view('renewals/index',['services'=>$db->query('SELECT s.id,s.nombre_servicio,s.fecha_vencimiento,c.razon_social FROM servicios s INNER JOIN clientes c ON c.id=s.cliente_id WHERE s.deleted_at IS NULL ORDER BY s.fecha_vencimiento ASC')->fetchAll(),'payments'=>$db->query('SELECT id,numero_factura FROM pagos WHERE deleted_at IS NULL ORDER BY id DESC')->fetchAll(),'renewals'=>(new Renewal())->latest(100)]); }
public function create(): void { Auth::requireRole(['Administrador','Operador']); verify_csrf(); (new Renewal())->renew(['servicio_id'=>(int)$_POST['servicio_id'],'fecha_nueva'=>$_POST['fecha_nueva'],'pago_id'=>$_POST['pago_id'],'notas'=>$_POST['notas']]); $this->redirect('/renovaciones'); }
public function update(): void { Auth::requireRole(['Administrador','Operador']); verify_csrf(); (new Renewal())->update((int)$_POST['id'],$_POST); $this->redirect('/renovaciones'); }
public function delete(): void { Auth::requireRole(['Administrador']); verify_csrf(); (new Renewal())->delete((int)$_POST['id']); $this->redirect('/renovaciones'); }
public function export(): void { Auth::requireRole(['Administrador','Operador','Solo lectura']); $rows=(new Renewal())->latest(1000); header('Content-Type:text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename=renovaciones.csv'); $o=fopen('php://output','w'); if($rows){fputcsv($o,array_keys($rows[0])); foreach($rows as $r){fputcsv($o,$r);} } fclose($o); exit; }
}
