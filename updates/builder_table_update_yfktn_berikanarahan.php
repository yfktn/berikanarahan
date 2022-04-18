<?php namespace Yfktn\BerikanArahan\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateYfktnBerikanarahan extends Migration
{
    public function up()
    {
        Schema::table('yfktn_berikanarahan_', function($table)
        {
            $table->string('berdasarkan_str', 500)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('yfktn_berikanarahan_', function($table)
        {
            $table->dropColumn('berdasarkan_str');
        });
    }
}