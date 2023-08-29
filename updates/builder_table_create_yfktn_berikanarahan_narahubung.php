<?php namespace Yfktn\BerikanArahan\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateYfktnBerikanarahanNarahubung extends Migration
{
    public function up()
    {
        Schema::create('yfktn_berikanarahan_narahubung', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('arahan_id')->unsigned()->index();
            $table->string('nama', 1024);
            $table->string('hp');
            $table->string('email')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('yfktn_berikanarahan_narahubung');
    }
}
