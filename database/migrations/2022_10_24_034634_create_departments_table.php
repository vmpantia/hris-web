<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('dep_name', 50);
            $table->bigInteger('dep_manager_id'); /* users id */
            $table->integer('dep_status'); /* 0 = Enabled, 1 = Disabled , 2 = Deletion */
            $table->timestamps(); /* created_At & updated_At*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
