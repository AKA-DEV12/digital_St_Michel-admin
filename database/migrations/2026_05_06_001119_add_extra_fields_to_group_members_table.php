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
        Schema::table('group_members', function (Blueprint $table) {
            $table->date('date_naissance')->nullable()->after('nom_prenom');
            $table->string('situation_professionnelle')->nullable()->after('responsabilite');
            $table->integer('nombre_enfant')->default(0)->after('situation_professionnelle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropColumn(['date_naissance', 'situation_professionnelle', 'nombre_enfant']);
        });
    }
};
