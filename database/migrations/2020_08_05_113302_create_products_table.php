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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("description_short")->nullable();
            $table->string("description_long")->nullable();
            $table->string("category")->nullable();
            $table->string("used")->nullable();
            $table->string("location")->nullable();
            $table->string("town")->nullable();
            $table->string("price")->nullable();
            $table->string('image')->nullable();
            $table->string("author")->nullable();
            $table->string("slug")->nullable();
            $table->foreignId("id_users")->nullable()->constrained('users');
            $table->tinyInteger("status")->nullable()->default(0);
            $table->tinyInteger("published")->nullable()->default(0);
            $table->tinyInteger("draft")->nullable()->default(0);  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
