<?php

namespace App\Exports;

use App\CategoriasModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */

class CategoriasExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $categorias =   DB::select('SELECT C.nombre_categoria,C.codigo_categoria,C.descripcion_categoria,
                                    C.created_at,C.updated_at,U.name
                                    FROM software_sg.categorias C LEFT JOIN software_sg.users U ON U.id = C.id_usuario
                                    ORDER BY id_categoria DESC');

        return collect($categorias);
    }
}

