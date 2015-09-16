<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'client_id',
        'name',
        'description',
        'progress',
        'status',
        'due_date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo('CodeProject\Entities\User');
    }

    public function notes()
    {
        return $this->hasMany(ProjectNote::class);
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members');
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

}
