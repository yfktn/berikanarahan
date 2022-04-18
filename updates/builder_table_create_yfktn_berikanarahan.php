<?php namespace Yfktn\BerikanArahan\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateYfktnBerikanarahan extends Migration
{
    public function up()
    {
        Schema::create('yfktn_berikanarahan_', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->text('arahan');
            $table->string('berdasarkan_id')->nullable()->index();
            $table->string('berdasarkan_type')->nullable()->index();
            $table->integer('pengarah_id')->nullable()->unsigned()->index();
            $table->date('deadline')->nullable();
            $table->string('status')->nullable()->default('LAGI_PROSES')->index();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('yfktn_berikanarahan_');
    }
}