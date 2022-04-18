<?php namespace Yfktn\BerikanArahan\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateYfktnBerikanarahanPersonil extends Migration
{
    public function up()
    {
        Schema::create('yfktn_berikanarahan_personil', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('personil_id')->unsigned()->integer();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('arahan_id')->unsigned()->integer();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('yfktn_berikanarahan_personil');
    }
}