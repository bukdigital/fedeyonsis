<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeagueCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'level',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function rules()
    {
        return $this->hasOne(CategoryRule::class);
    }

    public function ageCategories()
    {
        return $this->belongsToMany(AgeCategory::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
