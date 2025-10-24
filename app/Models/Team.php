<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function employes()
    {
        return $this->hasMany(Employe::class);
    }
}
