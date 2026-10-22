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
        'status',
        'priority',
        'category_id'
    ];

    protected $casts = [
        'priority' => TaskPriority::class,
        'status' => 'boolean',
        'due_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
