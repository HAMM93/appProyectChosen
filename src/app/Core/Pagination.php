<?php

namespace App\Core;

use http\Exception\InvalidArgumentException;

class Pagination
{
    private bool $has_more;
    private int $skip_value;
    private int $per_page;
    private int $count;

    private function setSkipValue(int $page, int $per_page)
    {
        if (($page <= 0) || $per_page <=0)
            throw new InvalidArgumentException();

        return ($per_page * $page) - $per_page;
    }

    private function setHasMore(int $page, int $total, int $per_page): bool
    {
        if (($page <= 0) || $per_page <=0)
            throw new InvalidArgumentException();

       return ($per_page * $page) < $total;
    }

    public function setPaginate($model_result, int $per_page, int $page, int $default_per_page)
    {
        $this->per_page = ($per_page < $default_per_page) ? $per_page : $default_per_page;

        $this->count = $model_result->count();

        $this->has_more = $this->setHasMore($page, $this->count, $this->per_page);

        $this->skip_value = $this->setSkipValue($page, $this->per_page);
    }

    public function getHasMore(): bool
    {
        return $this->has_more;
    }

    public function getSkipValue(): int
    {
        return $this->skip_value;
    }

    public function getPerPage(): int
    {
        return $this->per_page;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
