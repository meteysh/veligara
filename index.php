<?php

use App\Data\Buyer;
use App\Data\Order;
use App\Fba\CreateFulfillmentOrderBodyBuilder;
use App\Fba\ShippingService;
use App\Fba\ShippingSpeedCategory;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

require __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 'on');

const TRACKING_NUMBER = 12345;
const ORDER_NUMBER = 16400;
$order = new Order(ORDER_NUMBER);
$order->load();

$buyerData = json_decode(file_get_contents(__DIR__ . "/mock/buyer.29664.json"), true);
$buyer = new Buyer($buyerData);
$body = json_encode([
    "payload" => [
        "fulfillmentShipments" => [
            [
                "fulfillmentShipmentPackage" => [
                    ["trackingNumber" => (string)TRACKING_NUMBER]
                ]
            ]
        ]
    ]
]);

// Mock http client
$mock = new MockHandler([
    new Response(200, [], ''),
    new Response(
        200,
        [], $body
    ),
]);
$handlerStack = HandlerStack::create($mock);
$client = new Client(['handler' => $handlerStack]);

// Create Amazon's fulfillment network (FBA) service
$shippingSpeedCategory = new ShippingSpeedCategory();
$createFulfillmentOrderBodyBuilder = new CreateFulfillmentOrderBodyBuilder($shippingSpeedCategory);
$fba = new ShippingService($client, $createFulfillmentOrderBodyBuilder);

try {
    $trackingNumber = $fba->ship($order, $buyer);
} catch (Throwable $e) {
    echo sprintf("Error: %s" . PHP_EOL, $e->getMessage());
    exit(1);
}

echo 'tracking number is "' . $trackingNumber . '"' . PHP_EOL;
