<?php

declare(strict_types=1);

namespace App\Core\Donation;

use App\Core\Contracts\CartChosenInterface;
use App\Exceptions\DonorPayment\InvalidProductException;
use App\Models\Product;
use App\Services\Logging\Facades\Logging;
use App\Services\Payment\src\Providers\Contracts\CartInterface;
use App\Types\ProductTypes;

class Cart implements CartInterface, CartChosenInterface
{
    /**
     * Each donation can be only a specific number of items
     */
    protected const MAX_UNIQUE_ITEMS = 2;

    /**
     * @var string
     */
    protected string $revelationType;

    /**
     * @var int
     */
    protected int $revelationPrice;

    /**
     * @var int
     */
    protected int $donationPrice;

    /**
     * Total amount to be charged in transaction
     * @var int
     */
    public int $amount = 0;

    /**
     * A validated cart
     * @var array
     */
    public array $cartItems = [];

    /**
     * @var int
     */
    public int $donationQuantity;

    /**
     * @throws InvalidProductException
     */
    public function __construct(array $items, int $quantity)
    {
        $this->donationQuantity = $quantity;

        $products = Product::whereIn('id', $items)->get()->toArray();

        if (empty($products) || count($products) > self::MAX_UNIQUE_ITEMS) {
            throw new InvalidProductException();
        }

        foreach ($products as $product) {
            $this->amount += $quantity * intval($product['price']);
            $this->cartItems[] = [
                'id' => (string) $product['id'],
                'title' => $product['item'],
                'unit_price' => intval($product['price']),
                'quantity' => $quantity,
                'tangible' => (bool)$product['tangible']
            ];

            if ($product['description_type'] === ProductTypes::REVELATION_DIGITAL) {
                $this->revelationType = ProductTypes::REVELATION_DIGITAL;
                $this->revelationPrice = (int) $product['price'];
            } else if ($product['description_type'] === ProductTypes::REVELATION_PHYSICAL) {
                $this->revelationType = ProductTypes::REVELATION_PHYSICAL;
                $this->revelationPrice = (int) $product['price'];
            }

            if ($product['description_type'] === ProductTypes::DONATION_FOR_A_CHILD) {
                $this->donationPrice = (int) $product['price'];
            }

        }

        if (count($items) !== count($this->cartItems))
            Logging::security(['message' => trans('logging.item-not-recognized'), 'error_code' => 0]);

        if ($this->amount === 0)
            throw new InvalidProductException();
    }

    public function getItems(): array
    {
        return $this->cartItems;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTypeRevelation(): string
    {
        return $this->revelationType;
    }

    public function getRevelationPrice(): int
    {
        return $this->revelationPrice;
    }

    public function getDonationPrice(): int
    {
        return $this->donationPrice;
    }

    public function validateRevelation(): void
    {
        // TODO: Implement validateRevelation() method.
    }

    public function getDonationQuantity(): int
    {
        return $this->donationQuantity;
    }
}
