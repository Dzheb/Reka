<?php
namespace App\Utilities\PostFilters;

use App\Utilities\FilterContract;
use App\Utilities\QueryFilter;

class Task extends QueryFilter implements FilterContract
{

    public function handle($value): void
    {
        $this->query->where('name', 'like', "%$value%");
        // exact value ->where('title', $value);
    }
}
