<?php

namespace App\Data;

class Order extends AbstractOrder
{
    protected function loadOrderData(int $id): array
    {
        return json_decode(
            file_get_contents(
                __DIR__ . "/../../mock/order.{$id}.json"),
            true
        );
    }
}