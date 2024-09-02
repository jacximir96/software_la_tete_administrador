<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsumosModel extends Model
{
    protected $table = 'insumo';

    protected $fillable = [
        "IDInsumo",
        "codigo_insumo",
        "nombre_insumo",
        "descripcion_insumo",
        "stock_insumo",
        "id_unidadMedida",
        "id_usuario"
    ];

}
