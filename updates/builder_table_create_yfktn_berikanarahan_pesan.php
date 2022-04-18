<?php namespace Yfktn\BerikanArahan\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateYfktnBerikanarahanPesan extends Migration
{
    public function up()
    {
        Schema::create('yfktn_berikanarahan_pesan', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->text('pesan');
            $table->integer('arahan_id')->unsigned()->index();
            $table->integer('personil_id')->unsigned()->index();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('yfktn_berikanarahan_pesan');
    }
}