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
        Schema::create('catechists', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('nom_prenom');
            $table->date('date_naissance')->nullable();
            $table->string('photo')->nullable();
            $table->string('lieu_habitation')->nullable();
            $table->enum('situation_matrimoniale', ['Celibataire', 'Concubinage', 'Marier', 'Divorcer', 'Veuve / Veuf'])->nullable();
            $table->integer('nombre_enfant')->nullable();
            $table->boolean('antecedent')->default(false);
            $table->date('antecedent_date')->nullable();
            $table->string('antecedent_annee_catechese')->nullable();
            $table->string('antecedent_paroisse')->nullable();
            $table->boolean('groupe_mouvement')->default(false);
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('member_id')->nullable()->constrained('group_members')->onDelete('set null');
            $table->string('situation_professionnelle')->nullable();
            $table->boolean('baptiser')->default(false);
            $table->date('date_bapteme')->nullable();
            $table->string('paroisse_bapteme')->nullable();
            $table->string('carnet_bapteme')->nullable();
            $table->enum('annee_catechese', ['1ere', '2eme', '3eme', '4eme', '5eme']);
            $table->enum('statut_catechese', ['En cours', 'Terminee'])->default('En cours');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catechists');
    }
};
