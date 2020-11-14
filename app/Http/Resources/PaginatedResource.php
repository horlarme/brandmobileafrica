<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedResource extends ResourceCollection
{
    /**
     * @var LengthAwarePaginator
     */
    public $resource;
    private string $class;

    public function __construct(LengthAwarePaginator $resource, string $resourceClass)
    {
        parent::__construct($resource);
        $this->class = $resourceClass;
        $this->resource = $resource;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'meta' => [
                'perPage' => $this->resource->perPage(),
                'total' => $this->resource->total(),
                'lastPage' => $this->resource->lastPage(),
                'currentPage' => $this->resource->currentPage()
            ],
            'data' => collect($this->resource->items())->map(fn($item) => new $this->class($item))
        ];
    }
}
