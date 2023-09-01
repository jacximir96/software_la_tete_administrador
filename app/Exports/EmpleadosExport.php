<?php

namespace App\Exports;

use App\EmpleadosModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */

class EmpleadosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $empleados =DB::select('SELECT E.nombres_empleado,E.apellidos_empleado,E.dni_empleado,
                                E.created_at,E.updated_at,C.nombre_cargo,U.name FROM empleados E LEFT JOIN cargos C 
                                ON E.id_cargo = C.id_cargo LEFT JOIN users U ON U.id= E.id_usuario ORDER BY E.id_empleado DESC');

        return collect($empleados);
    }
}
