<?php

declare(strict_types=1);

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
        Schema::create('activities', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('source')->nullable();
            $table->string('short_description', 200)->nullable();
            $table->string('registration_link')->nullable();
            $table->json('coordinates')->nullable();
            $table->json('schedule')->nullable();

            $table->foreignId('activity_type_id')
                ->nullable()
                ->constrained('activity_types')
                ->onDelete('set null');
            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->foreignId('participant_id')
                ->nullable()
                ->constrained('participants')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
