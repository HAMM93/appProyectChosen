<?php

declare(strict_types=1);

namespace App\Core\Donation;

use App\Exceptions\Card\InvalidCardException;
use App\Services\Payment\src\Providers\Contracts\CardInterface;

class Card implements CardInterface
{
    private string $number;
    private string $cvc;
    private string $expMonth;
    private string $expYear;
    private string $holderName;

    /**
     * @throws InvalidCardException
     */
    public function __construct(array $card)
    {
        $this->number = $card['number'];
        $this->cvc = $card['cvc'];
        $this->expMonth = $card['exp_month'];
        $this->expYear = $card['exp_year'];
        $this->holderName = $card['holder_name'];

        $this->validateCard();
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getCVC(): string
    {
        return $this->cvc;
    }

    public function getExpMonth(): string
    {
        return $this->expMonth;
    }

    public function getExpYear(): string
    {
        return $this->expYear;
    }

    public function getHolderName(): string
    {
        return $this->holderName;
    }

    public function validateCard()
    {
        $month = intval($this->getExpMonth());
        $year = intval($this->getExpYear());

        if (
            $year < date('y')
            || ($year === intval(date('y')) && $month < intval(date('m')))
        ) {
            throw new InvalidCardException();
        }
    }
}
