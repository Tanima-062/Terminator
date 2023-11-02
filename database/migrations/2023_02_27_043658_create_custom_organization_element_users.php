<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\OrganizationElement;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_organization_element_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrganizationElement::class, 'organization_element_id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->timestamps();
            $table->unique(['organization_element_id', 'user_id'], 'unique_custom_organization_element_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_organization_element_users');
    }
};
