<?php

declare(strict_types=1);


namespace App\Repositories;


abstract class AbstractRepository
{
    public bool $has_more = false;

    public int $count;

    public int $skip;

    public int $per_page;

    public function getSkipValue(int $page, int $per_page): void
    {
        if (($page <= 0) || $per_page <= 0)
            throw new \InvalidArgumentException();

        $per_page = ($per_page < $this->per_page) ? $per_page : $this->per_page;

        $this->skip = ($per_page * $page) - $per_page;
    }

    public function hasMore(int $page, int $total, int $per_page): void
    {
        if (($page <= 0) || $per_page <= 0)
            throw new \InvalidArgumentException();

        $per_page = ($per_page < $this->per_page) ? $per_page : $this->per_page;

        $this->has_more = ($per_page * $page) < $total;
    }

    public function setPaginate($result, int $per_page, int $page)
    {
        $this->count = count($result->get()->toArray());

        $this->hasMore($page, $this->count, $per_page);

        $this->getSkipValue($page, $per_page);

        return $result->skip($this->skip)->take($per_page)->get();
    }
}
