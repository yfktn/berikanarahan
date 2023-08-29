<?php namespace Yfktn\BerikanArahan\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateYfktnBerikanarahanPersonil extends Migration
{
    public function up()
    {
        Schema::table('yfktn_berikanarahan_personil', function($table)
        {
            $table->string('tugas', 2024)->nullable();
            $table->text('keterangan')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('yfktn_berikanarahan_personil', function($table)
        {
            $table->dropColumn('tugas');
            $table->dropColumn('keterangan');
        });
    }
}
