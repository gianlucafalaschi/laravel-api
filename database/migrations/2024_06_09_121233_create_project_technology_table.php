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
        Schema::create('project_technology', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')
            ->references('id')
            ->on('projects')
            ->onDelete('cascade');
            

            $table->unsignedBigInteger('technology_id');
            $table->foreign('technology_id')
            ->references('id')
            ->on('technologies')
            ->onDelete('cascade');

            // per motivi di performance è consigliato nelle tabelle ponte mettere 
            //entrambe le colonne come primary key
            $table->primary(['project_id', 'technology_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_technology');
    }
};
