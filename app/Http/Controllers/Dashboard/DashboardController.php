<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    public function __construct(){
        $this -> setLog('DashboardController.log');
    }

    public function index(){

        // Verificar si el usuario tiene acceso a reportes y gráficas
        // Este apartado incluye dentro las notificaciones
        if ( user() -> can('REPO.GENERAR.REPORTE') )
        {

        }

        // Verificar si el usuario tiene acceso a recepción de documentos locales
        if ( user() -> can('REC.DOCUMENTO.LOCAL') )
        {
            return redirect('recepcion/documentos/recepcionados');
        }

        // Verificar si el usuario tiene acceso a recepción de documentos foráneos
        if ( user() -> can('REC.DOCUMENTO.FORANEO') )
        {
            
        }

        // Verificar si el usuario tiene acceso a panel de trabajo de documentos
        if ( user() -> can('SEG.PANEL.TRABAJO') )
        {
            
        }

        // Verificar si el usuario tiene acceso a la administración del sistema
        if ( user() -> can('REPO.GENERAR.REPORTE') )
        {
            
        }

        // Si no tiene accesos, hay que mostrar una pantalla por default del sistema

        return view('Dashboard.indexDashboard');
    }

}
