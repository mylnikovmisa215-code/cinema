<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('screen_id')
                ->constrained('screenings')
                ->cascadeOnDelete();

            $table->integer('row');
            $table->integer('seat');

            $table->boolean('is_paid')->default(false);

            $table->string('unque_code')->unique()->nullable();

            $table->timestamps();

            // Одно место на одном сеансе нельзя продать дважды
            $table->unique(
                ['screen_id', 'row', 'seat'],
                'tickets_screen_seat_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};