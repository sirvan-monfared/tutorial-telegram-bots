<?php

namespace App\Core;

use eftec\bladeone\BladeOne;

class Paginator
{
    protected string $url;
    protected int $page_number;
    protected array $queries;

    public function __construct(protected int $per_page = 10, protected int $total = 0)
    {
        $this->url = currentUrl();

        $this->queries = $this->getQueryStrings();

        $this->page_number = intval($_GET['page'] ?? 1);
    }

    public function perPage(): int
    {
        return $this->per_page;
    }

    public function hasPrevPage(): bool
    {
        return $this->page_number > 1;
    }

    public function hasNextPage(): bool
    {
        return $this->page_number < $this->lastPage();
    }

    public function offset(): int
    {
        return ($this->page_number - 1) * $this->per_page;
    }

    public function lastPage(): int
    {
        return ceil($this->total / $this->per_page);
    }

    public function prevUrl(): string
    {
        return $this->generateUrl($this->page_number - 1);
    }

    public function nextUrl(): string
    {
        return $this->generateUrl($this->page_number + 1);
    }

    public function generateUrl($page_number): string
    {
        $this->queries['page'] = $page_number;

        return "{$this->url}?". http_build_query($this->queries);
    }

    public function getQueryStrings(): array
    {
        $queries = parse_url($_SERVER['REQUEST_URI'])['query'] ?? '';
        parse_str($queries, $query_array);

        return array_filter($query_array);
    }

    public function isCurrentPage(int $page_number): bool
    {
        return $page_number === $this->page_number;
    }

    public function render()
    {
        return App::resolve('blade')->run('admin.partials._pagination', [
            'paginator' => $this
        ]);
    }
}