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
        Schema::create(config('laravel-dbconfig.tables.configurations', 'configurations'), function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->string('title')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('main');
            $table->string('value')->nullable();
            $table->integer('count')->nullable();
            $table->integer('max')->nullable();
            $table->integer('col')->nullable()->default(12);
            $table->string('hint')->nullable();
            $table->boolean('autogrow')->default(false);
            $table->boolean('secret')->default(false);
            $table->json('choices')->nullable();
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
        Schema::dropIfExists(config('laravel-dbconfig.tables.configurations', 'configurations'));
    }
};
