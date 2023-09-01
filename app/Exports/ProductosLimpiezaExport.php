<?php

namespace App\Exports;

use App\ProductosLimpiezaModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */

class ProductosLimpiezaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $productosLimpieza =   DB::select('SELECT PL.codigo_productoLimpieza,PL.nombre_productoLimpieza,
                                    PL.descripcion_productoLimpieza,PL.stock_productoLimpieza,C.nombre_categoria,
                                    PL.created_at,PL.updated_at,
                                    U.name FROM productos_limpieza PL INNER JOIN categorias C ON
                                    C.id_categoria = PL.id_categoria LEFT JOIN users U ON U.id = PL.id_usuario ORDER BY id_productoLimpieza DESC');

        return collect($productosLimpieza);
    }
}
