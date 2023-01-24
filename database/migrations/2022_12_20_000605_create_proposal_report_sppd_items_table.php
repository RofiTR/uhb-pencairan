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
        Schema::create('proposal_report_sppd_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('proposal_report_sppd_id');
            $table->uuid('user_id')->nullable();
            $table->string('name');
            $table->tinyInteger('qty');
            $table->double('price', 15, 2);
            $table->double('sub_total', 15, 2);
            $table->double('credit', 15, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('proposal_report_sppd_id')->references('id')->on('proposal_report_sppds')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_report_sppd_items');
    }
};
