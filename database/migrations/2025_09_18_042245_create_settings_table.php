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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('footer'); // footer, seo, header, etc.
            $table->string('key');
            $table->text('value')->nullable();
            $table->boolean('hide')->default(false);
            $table->string('image')->default(null)->nullable();
            $table->unique(['group', 'key']); // prevent duplicates in same group
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};