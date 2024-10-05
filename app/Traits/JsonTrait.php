<?php

namespace App\Traits;

use App\Models\Sale;
use App\Models\SaleDetail;

trait JsonTrait
{

    function jsonData($sale_id)
    {
        // venta - detalle -product - cliente - user 

        $sale = Sale::find($sale_id);

        $detalle = SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')
            ->select('sale_details.*', 'p.name')
            ->where('sale_details.sale_id', $sale_id)
            ->orderBy('p.name')
            ->get();


        $cliente = $sale->customer;

        $user = $sale->user;

        $json =  $sale->toJson() . '|' . $detalle->toJson() . '|' . $cliente->toJson() . '|' . $user->toJson();

        $b64  = base64_encode($json);

        return $b64;
    }


    function jsonData2($sale_id)
    {

        $sale = Sale::select('id', 'user_id', 'customer_id', 'total', 'items', 'status', 'type', 'cash', 'change')
            ->find($sale_id);

        $detalle = $sale->details()->select('product_id', 'quantity', 'sale_price', 'discount')
            ->with('product:id,name')
            ->get();


        $cliente = $sale->customer; // id, name
        $user = $sale->user; //id, name


        $json =  $sale->toJson() . '|' . $detalle->toJson() . '|' . $cliente->toJson() . '|' . $user->toJson();

        $b64  = base64_encode($json);

        return $b64;
    }


    function jsonData3($sale_id)
    {

        //version ninja
        $sale = Sale::select('id', 'user_id', 'customer_id', 'total', 'items', 'status', 'type', 'cash', 'change')
            ->with(['user' => function ($query) {
                $query->select('id', 'name as user_name');
            }, 'customer' => function ($query) {
                $query->select('id', 'name as customer_name');
            }, 'details' => function ($query) {
                $query->select('sale_id', 'product_id', 'quantity', 'sale_price', 'discount');
            }, 'details.product' => function ($query) {
                $query->select('id', 'name');
            }])
            ->find($sale_id);

        $json =  $sale->toJson();

        $b64  = base64_encode($json);

        return $b64;
    }
}
