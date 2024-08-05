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
            $filters['search'] ?? null, // If search parameter is set, apply the filter
            function ($query, $search) { // Callback function to apply the filter
                $query->where(function ($query) use ($search) { // Use a closure to apply multiple where conditions
                    $query->where('title', 'like', '%' . $search . '%') // Search in title field
                        ->orWhere('description', 'like', '%' . $search . '%') // Search in description field
                        ->orWhereHas('employer', function ($query) use ($search) { // Search in employer's company name
                        $query->where('company_name', 'like', '%' . $search . '%');
                    });
                });
            }
        )
            ->when(
                $filters['min_salary'] ?? null, // Apply minimum salary filter
                function ($query, $minSalary) {
                    $query->where('salary', '>=', $minSalary);
                }
            )
            ->when(
                $filters['max_salary'] ?? null, // Apply maximum salary filter
                function ($query, $maxSalary) {
                    $query->where('salary', '<=', $maxSalary);
                }
            )
            ->when(
                $filters['experience'] ?? null, // Apply experience filter
                function ($query, $experience) {
                    $query->where('experience', $experience);
                }
            )
            ->when(
                $filters['category'] ?? null, // Apply category filter
                function ($query, $category) {
                    $query->where('category', $category);
                }
            );
    }
}
