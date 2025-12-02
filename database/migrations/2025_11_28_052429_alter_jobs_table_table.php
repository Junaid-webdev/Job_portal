<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('jobs_table', function (Blueprint $table) {
        // user_id column already exists, so don't add it again
        $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('jobs_table', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
    });
}
};
