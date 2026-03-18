<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->string('session_id', 100)->nullable()->after('id');
        });

        DB::table('favorites')->whereNull('session_id')->update([
            'session_id' => 'legacy',
        ]);

        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique('favorites_tmdb_id_unique');
            $table->index('session_id');
            $table->unique(['session_id', 'tmdb_id'], 'favorites_session_tmdb_unique');
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique('favorites_session_tmdb_unique');
            $table->dropIndex(['session_id']);
            $table->unique('tmdb_id');
            $table->dropColumn('session_id');
        });
    }
};
