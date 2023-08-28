<?php

namespace App\Exports;

use App\UnidadesMedidaModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */

class UnidadesMedidaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $unidadesMedida =   DB::select('SELECT UM.nombre_unidadMedida,E.nombre_estado,
                                        UM.created_at,UM.updated_at,U.name FROM
                                        unidades_medida UM INNER JOIN estado E ON
                                        UM.estado_unidadMedida = E.id_estado
                                        LEFT JOIN users U ON UM.id_usuario = U.id
                                        ORDER BY UM.id_unidadMedida DESC');

        return collect($unidadesMedida);
    }
}
