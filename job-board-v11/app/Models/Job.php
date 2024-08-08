<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function hasUserApplied(Authenticatable|User|int $user): bool
    {
        $user_id = $user instanceof User ? $user->id : $user;
        return $this->where('id', $this->id)
            ->whereHas(
                'jobApplications',
                fn($query) => $query->where('user_id', '=', $user_id)
            )->exists();
    }

    public function scopeFilter(
        Builder|QueryBuilder $query,
        array $filters
    ): Builder|QueryBuilder {
        return $query
            ->when(
                $filters['search'] ?? null,
                fn($query, $search) => $query->where(
                    fn($query) => $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhereHas('employer', fn($query) => $query->where('company_name', 'like', '%' . $search . '%'))
                )
            )
            ->when($filters['min_salary'] ?? null, fn($query, $minSalary) => $query->where('salary', '>=', $minSalary))
            ->when($filters['max_salary'] ?? null, fn($query, $maxSalary) => $query->where('salary', '<=', $maxSalary))
            ->when($filters['experience'] ?? null, fn($query, $experience) => $query->where('experience', $experience))
            ->when($filters['category'] ?? null, fn($query, $category) => $query->where('category', $category));
    }
}
