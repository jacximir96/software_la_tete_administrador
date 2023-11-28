<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleOrdenPedidoModel extends Model
{
    protected $table = 'detalle_orden_pedido';
    public $fillable = ['IDDetalleOrdenPedido','IDProducto','dop_cantidad','dop_precio','dop_total'];
}
