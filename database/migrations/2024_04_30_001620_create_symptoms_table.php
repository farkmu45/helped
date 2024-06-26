<?php

use App\Models\Disease;
use App\Models\Symptom;
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
        Schema::create('symptoms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->float('belief', 2);
            $table->float('disbelief', 2)->virtualAs('1 - belief');
        });

        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
        });

        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Disease::class)
                ->constrained()
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreignIdFor(Symptom::class)
                ->constrained()
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symptoms');
        Schema::dropIfExists('diseases');
        Schema::dropIfExists('rules');
    }
};
