<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Models\Project;
use App\Models\User;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'assigned_to',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeAssignedTo($query, ?int $userId)
    {
        return $userId ? $query->where('assigned_to', $userId) : $query;
    }

    public function scopeDueBefore($query, ?string $date)
    {
        return $date ? $query->whereDate('due_date', '<=', $date) : $query;
    }

    public function scopeDueAfter($query, ?string $date)
    {
        return $date ? $query->whereDate('due_date', '>=', $date) : $query;
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->status !== 'done' && $this->due_date->lt(Carbon::today());
    }
}
