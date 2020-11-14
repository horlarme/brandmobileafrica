<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    private bool $includeChoices;

    public function __construct($resource, $includeChoices = false)
    {
        parent::__construct($resource);
        $this->includeChoices = $includeChoices;
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        if (!$this->includeChoices) unset($data['choices']);
        else {
            //using this line in case the data wasn't autoloaded
            $data['choices'] = $this->resource->choices;
        }
        return $data;
    }
}
