<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class TitleOrContentFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $values = \is_array($value) ? $value : [$value];

        $query->where(function (Builder $query) use ($values) {
            foreach ($values as $val) {
                $query->orWhere('title', 'LIKE', "%{$val}%")
                    ->orWhere('content', 'LIKE', "%{$val}%");
            }
        });
    }
}
