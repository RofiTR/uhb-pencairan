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
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('context')->nullable();
            $table->uuid('context_id');
            $table->string('name')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->bigInteger('file_size');
            $table->string('mime_type', 100)->nullable();
            $table->tinyInteger('sort_order');
            $table->uuid('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
