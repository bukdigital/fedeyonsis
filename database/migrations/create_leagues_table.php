<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('league_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('level')->comment('Lig seviyesi (1: En üst lig)');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Yaş kategorileri tablosu
        Schema::create('age_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('min_age');
            $table->integer('max_age');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Kategori kuralları tablosu
        Schema::create('category_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_category_id')->constrained()->onDelete('cascade');
            $table->integer('max_foreign_players');
            $table->integer('min_players_squad');
            $table->integer('max_players_squad');
            $table->integer('relegation_count')->comment('Düşme sayısı');
            $table->integer('promotion_count')->comment('Yükselme sayısı');
            $table->integer('points_win')->default(3);
            $table->integer('points_draw')->default(1);
            $table->integer('points_lose')->default(0);
            $table->timestamps();
        });

        // Lig kategorisi - Yaş kategorisi pivot tablosu
        Schema::create('age_category_league_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('age_category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('age_category_league_category');
        Schema::dropIfExists('category_rules');
        Schema::dropIfExists('age_categories');
        Schema::dropIfExists('league_categories');
    }
};
