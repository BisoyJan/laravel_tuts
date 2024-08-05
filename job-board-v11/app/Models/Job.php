<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Job extends Model
{
    use HasFactory;

    public static array $experience = ['entry', 'intermediate', 'senior'];
    public static array $category = [
        'IT',
        'Finance',
        'Sales',
        'Marketing'
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    /**
     * Filter jobs by search, salary, experience and category.
     *
     * @param Builder|QueryBuilder $query The query builder instance.
     * @param array $filters The filters to apply.
     * @return Builder|QueryBuilder The filtered query builder instance.
     */
    public function scopeFilter(Builder|QueryBuilder $query, array $filters): Builder|QueryBuilder
    {
        // Apply search filter
        return $query->when(
            $filters['search'] ?? null,
            function ($query, $search) {
                // Search in title and description fields
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            }
        )->when(
                // Apply minimum salary filter
                $filters['min_salary'] ?? null,
                function ($query, $minSalary) {
                    $query->where('salary', '>=', $minSalary);
                }
            )->when(
                // Apply maximum salary filter
                $filters['max_salary'] ?? null,
                function ($query, $maxSalary) {
                    $query->where('salary', '<=', $maxSalary);
                }
            )->when(
                // Apply experience filter
                $filters['experience'] ?? null,
                function ($query, $experience) {
                    $query->where('experience', $experience);
                }
            )->when(
                // Apply category filter
                $filters['category'] ?? null,
                function ($query, $category) {
                    $query->where('category', $category);
                }
            );
    }
}
