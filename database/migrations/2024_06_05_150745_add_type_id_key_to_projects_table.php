<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // alla tabella projects viene aggiunto la colonna foreign key type_id 
            // che si riferisce alla colonna id della tabella types
            $table->unsignedBigInteger('type_id')->nullable()->after('id');
            
            // questo e' il nome dato alla regola per convenzione: projects_type_id_foreign
            $table->foreign('type_id')
            ->references('id')
            ->on('types')
            ->onDelete('set null');  // se un type viene cancellato in projects rimarrebbe una foreign key orfana, per evtare questa la settiamo su null  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_type_id_foreign'); // nometabella_nomecolonna_foreign
            $table->dropColumn('type_id');
        });
    }
};
