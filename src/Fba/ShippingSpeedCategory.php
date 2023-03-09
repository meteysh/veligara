<?php

declare(strict_types=1);

namespace App\Fba;

use RuntimeException;

class ShippingSpeedCategory
{
    /**
     * @throws RuntimeException
     */
    public function getByTypeId(int $shippingTypeId): string
    {
        /** developer-docs.amazon.com/sp-api/docs/fulfillment-outbound-api-v2020-07-01-reference#shippingspeedcategory */
        switch ($shippingTypeId) {
            case 1:
                $result = 'Standard';
                break;
            case 2:
                $result = 'Expedited';
                break;
            case 3:
                $result = 'Priority';
                break;
            case 7:
                $result = 'ScheduledDelivery';
                break;
            default:
                throw new RuntimeException('unknown ShippingSpeedCategory ' . $shippingTypeId);
        }
        return $result;
    }
}
