<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskPriority;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'due_at',
        'status_id',
        'priority',
        'category_id'
    ];

    public const SORTABLE_COLUMNS = [
        'due_at',
        'created_at',
        'title',
        'priority',
        'status_id',
        'category_id'
    ];

    protected $casts = [
        'priority' => TaskPriority::class,
        'due_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
