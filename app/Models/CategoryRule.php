<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_category_id',
        'max_foreign_players',
        'min_players_squad',
        'max_players_squad',
        'relegation_count',
        'promotion_count',
        'points_win',
        'points_draw',
        'points_lose'
    ];

    public function leagueCategory()
    {
        return $this->belongsTo(LeagueCategory::class);
    }
}
