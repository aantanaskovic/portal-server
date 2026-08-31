<?php

namespace App\Models;

use App\Filters\TitleOrContentFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'content'
    ];

    public function scopeForIndex(Builder $query): QueryBuilder
    {
        $allowedRelations = ['user', 'userCount', 'userExists'];

        if (request()->has('include')) {
            $requestedIncludes = explode(',', request()->input('include'));

            $filteredIncludes = array_intersect($requestedIncludes, $allowedRelations);

            request()->merge([
                'include' => implode(',', $filteredIncludes)
            ]);
        } else {
            $query->with('user');
        }

        return QueryBuilder::for($query)
            ->allowedFilters(
                AllowedFilter::custom('search', new TitleOrContentFilter),
                'title',
                'content'
            )
            ->allowedIncludes('user')
            ->latest();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
