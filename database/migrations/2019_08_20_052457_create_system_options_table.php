<?php
/**
 * phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSystemOptionsTable
 *
 * @since 0.1.0
 */
class CreateSystemOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 150);
            $table->text('value');
            $table->string('type', 150 )->nullable();
            $table->boolean('autoload')->default(false);

            $table->unique('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_options');
    }
}
