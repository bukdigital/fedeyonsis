<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgeCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'min_age',
        'max_age',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function leagueCategories()
    {
        return $this->belongsToMany(LeagueCategory::class);
    }
}
