<?php
namespace App\Utilities\PostFilters;

use App\Utilities\FilterContract;
use App\Utilities\QueryFilter;

class Tag extends QueryFilter implements FilterContract
{
    
    public function handle($value): void
    {
        $this->query->whereHas('tags', function ($q) use ($value) {
            return $q->where('name', $value);
        });
    }
}
