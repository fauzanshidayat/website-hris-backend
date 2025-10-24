<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'logo'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_company');
    }
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
