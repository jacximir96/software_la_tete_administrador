<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductosLimpiezaModel extends Model
{
    protected $table = 'productos_limpieza';

    protected $fillable = [
        "id_productoLimpieza",
        "codigo_productoLimpieza",
        "nombre_productoLimpieza",
        "descripcion_productoLimpieza",
        "stock_productoLimpieza",
        "id_unidadMedida",
        "id_categoria",
        "imagen_productoLimpieza",
        "id_usuario",
        "nombres_insumos"
    ];

}
