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
        Schema::create(config('laravel-dbconfig.tables.fileables', 'fileables'), function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('fileable');
            $table->text('description')->nullable();
            $table->string('model')->nullable();
            $table->string('file');
            $table->string('fileable_collection')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('laravel-dbconfig.tables.fileables', 'fileables'));
    }
};
