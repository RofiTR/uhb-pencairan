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
        Schema::create('site_menu_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('menu_id');
            $table->uuid('parent_id')->nullable();
            $table->string('name');
            $table->jsonb('permission')->nullable();
            $table->string('label');
            $table->string('route');
            $table->string('url');
            $table->unsignedInteger('order')->default('0');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('menu_id')->references('id')->on('site_menus')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_menu_items');
    }
};
