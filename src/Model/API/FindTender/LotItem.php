<?php

namespace App\Model\API\FindTender;

class LotItem
{
    public ?string $id = null;

    /**
     * @var Classification[]|null
     */
    public ?array $additionalClassifications = null;

    /**
     * @var DeliveryAddresses[]|null
     */
    public ?array $deliveryAddresses = null;
}
