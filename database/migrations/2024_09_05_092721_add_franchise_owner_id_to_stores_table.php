<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFranchiseOwnerIdToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->unsignedBigInteger('franchise_owner_id')->after('brand_id');

            // Add foreign key constraint
            $table->foreign('franchise_owner_id')->references('id')->on('franchise_owners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['franchise_owner_id']);

            $table->dropColumn('franchise_owner_id');
        });
    }
}
