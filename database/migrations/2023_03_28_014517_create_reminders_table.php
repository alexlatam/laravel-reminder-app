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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor( App\Models\User::class )->constrained()->cascadeOnDelete();
            $table->string('reminder_text', 200);
            $table->date('reminder_day')->index();
            $table->time('reminder_hour')->index();
            $table->timestamp('reminder_timezone_date')->comment("Fecha real en la que notificamos al usuario");
            $table->string('reminder_timezone')->default("America/Caracas");
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
