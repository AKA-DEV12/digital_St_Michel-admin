<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_group_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_member_id')->constrained()->cascadeOnDelete();
            $table->string('responsabilite')->nullable();
            $table->date('date_adhesion')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Un membre ne peut être qu'une seule fois dans un même groupe
            $table->unique(['group_id', 'group_member_id']);
        });

        // Migration des données existantes (très important pour ne rien perdre)
        DB::statement('
            INSERT INTO group_group_member (group_id, group_member_id, responsabilite, date_adhesion, created_by, created_at, updated_at)
            SELECT group_id, id, responsabilite, date_adhesion, created_by, created_at, updated_at
            FROM group_members
            WHERE group_id IS NOT NULL
        ');

        // Nettoyage de la table d'origine
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn(['group_id', 'responsabilite', 'date_adhesion']);
        });
    }

    public function down(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('responsabilite')->nullable();
            $table->date('date_adhesion')->nullable();
        });

        Schema::dropIfExists('group_group_member');
    }
};
