<?php

namespace App\Exports;

use App\GastosModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class GastosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
/*         $gastos =   DB::select('SELECT GA.descripcion_gasto,E.nombre_estado,
                                        GA.created_at,GA.updated_at,U.name FROM
                                        gastos GA INNER JOIN estado E ON
                                        GA.estado_gasto = E.id_estado
                                        LEFT JOIN users U ON GA.id_usuario = U.id
                                        ORDER BY GA.id_gasto DESC'); */
        $gastos =   DB::select('SELECT GA.descripcion_gasto,GA.estado_gasto,
                GA.created_at,GA.updated_at,E.nombre_estado,U.name,GA.precio_gasto,
                GA.cantidad_gasto,GA.preciot_gasto,UM.nombre_unidadMedida FROM
                gastos GA INNER JOIN unidades_medida UM ON GA.id_unidadMedida = UM.id_unidadMedida
                INNER JOIN estado E ON GA.estado_gasto = E.id_estado
                LEFT JOIN users U ON GA.id_usuario = U.id 
                /* WHERE GA.control_gasto = 0 */ ORDER BY GA.id_gasto DESC');

        return collect($gastos);
    }
}
