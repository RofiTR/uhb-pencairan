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
        Schema::create('proposal_report_sppd_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('proposal_report_sppd_id');
            $table->uuid('user_id');
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
        Schema::dropIfExists('proposal_report_sppd_members');
    }
};
