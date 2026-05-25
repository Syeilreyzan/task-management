<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'project_admin_id'])]
class Project extends Model
{
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_admin_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('priority');
    }
}
