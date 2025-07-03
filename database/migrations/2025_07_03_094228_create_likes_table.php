<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('likes', function (Blueprint $table) {
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->morphs('likeable'); // This creates likeable_id and likeable_type
        $table->timestamps();

        // Add a primary key to prevent a user from liking the same item multiple times
        $table->primary(['user_id', 'likeable_id', 'likeable_type']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
