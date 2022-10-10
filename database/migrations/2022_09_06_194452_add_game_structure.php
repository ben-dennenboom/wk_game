<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('slug')->index()->unique();
        });

        Schema::create('tournaments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('leagues', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tournament_id')->references('id')->on('tournaments');
            $table->string('title');
            $table->boolean('is_general')->default(0);
            $table->timestamps();
        });

        Schema::create('user_league', function (Blueprint $table) {
            $table->foreignUuid('league_id')->references('id')->on('leagues');
            $table->foreignUuid('user_id')->references('slug')->on('users');
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('icon');

            $table->uuid('group_id')->nullable()->index();
            $table->foreign('group_id')->references('id')->on('groups');

            $table->timestamps();
        });

        Schema::create('teams_groups_tournaments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('team_id')->references('id')->on('teams');
            $table->foreignUuid('group_id')->references('id')->on('groups');
            $table->foreignUuid('tournament_id')->references('id')->on('tournaments');

            $table->timestamps();
        });

        Schema::create('stages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('number')->nullable();
            $table->foreignUuid('tournament_id')->references('id')->on('tournaments');

            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('slug')->nullable();
            $table->foreignUuid('tournament_id')->references('id')->on('tournaments');
            $table->foreignUuid('stage_id')->references('id')->on('stages');

            $table->uuid('group_id')->nullable()->index();
            $table->foreign('group_id')->references('id')->on('groups');

            $table->uuid('home_team_id')->nullable()->index();
            $table->uuid('away_team_id')->nullable()->index();

            $table->foreign('home_team_id')->references('id')->on('teams');
            $table->foreign('away_team_id')->references('id')->on('teams');

            $table->uuid('winner_id')->nullable()->index();
            $table->foreign('winner_id')->references('id')->on('teams');

            $table->uuid('loser_id')->nullable()->index();
            $table->foreign('loser_id')->references('id')->on('teams');

            $table->boolean('end_in_draw')->default(0);

            $table->integer('home_team_score')->nullable();
            $table->integer('away_team_score')->nullable();

            $table->integer('home_team_penalty_score')->nullable();
            $table->integer('away_team_penalty_score')->nullable();

            $table->dateTime('start')->nullable();

            $table->boolean('finished')->default(0);

            $table->boolean('is_calculated')->default(0);

            $table->string('get_home_from_slug')->nullable();
            $table->string('get_away_from_slug')->nullable();

            $table->uuid('home_from_winner_game_id')->nullable()->index();
            $table->uuid('away_from_winner_game_id')->nullable()->index();

            $table->foreign('home_from_winner_game_id')->references('id')->on('games');
            $table->foreign('away_from_winner_game_id')->references('id')->on('games');

            $table->uuid('home_from_loser_game_id')->nullable()->index();
            $table->uuid('away_from_loser_game_id')->nullable()->index();

            $table->foreign('home_from_loser_game_id')->references('id')->on('games');
            $table->foreign('away_from_loser_game_id')->references('id')->on('games');

            $table->timestamps();
        });

        Schema::create('predictions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->references('slug')->on('users');
            $table->foreignUuid('game_id')->references('id')->on('games');

            $table->foreignUuid('home_team_id')->references('id')->on('teams');
            $table->foreignUuid('out_team_id')->references('id')->on('teams');

            $table->uuid('winner_id')->nullable()->index();
            $table->foreign('winner_id')->references('id')->on('teams');

            $table->integer('home_team_score')->nullable();
            $table->integer('out_team_score')->nullable();

            $table->integer('score')->nullable();

            $table->timestamps();
        });

        Schema::create('scores', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')->references('slug')->on('users');

            $table->integer('score');
            $table->timestamps();
        });
    }
};
