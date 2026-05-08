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
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->string('nom_prenom');
            $table->string('photo')->nullable();
            $table->date('date_adhesion');
            $table->string('responsabilite')->nullable();
            $table->enum('situation_matrimoniale', ['Celibataire', 'Concubinage', 'Marier', 'Divorcer', 'Veuve / Veuf']);
            $table->timestamps();
            
            $table->index(['nom_prenom', 'date_adhesion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
