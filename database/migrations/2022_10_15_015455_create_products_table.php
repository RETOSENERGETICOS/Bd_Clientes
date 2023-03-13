<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('countrys', static function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        
        Schema::create('turns', static function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('servicess', static function(Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->timestamps();
        });

        Schema::create('distributions', static function(Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->timestamps();
        });

        Schema::create('trainings', static function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
         });

        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('item')->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->foreignId('turn_id')->constrained();
            $table->foreignId('services_id')->constrained();
            $table->foreignId('distribution_id')->constrained();
            $table->foreignId('training_id')->constrained();
            $table->string('tradename');
            $table->string('bname');
            $table->string('fvn');
            $table->string('address');
            $table->string('contact');
            $table->decimal('phone');
            $table->decimal('phone1');
            $table->string('mail');
            $table->string('payments');
            $table->decimal('phonee');
            $table->decimal('phonee1');
            $table->string('maill');
            $table->string('terms');
            $table->string('credit');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tools');
        Schema::dropIfExists('countrys');
        Schema::dropIfExists('turns');
        Schema::dropIfExists('servicess');
        Schema::dropIfExists('distributions');
        Schema::dropIfExists('trainings');
    }
}
