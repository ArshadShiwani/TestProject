<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Company;
class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */



    public function up()
    {

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            
            $table->string('phone');
            $table->string('company_img')->default("No image");
            $table->string('address');
            $table->string('email');
            
            $table->timestamps();
        });

        Company::create([
                'phone' => '1231',
                'company_img' => 'No Image',
                'address' =>'xyz',
                'email' => 'admin@gmail.com'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
