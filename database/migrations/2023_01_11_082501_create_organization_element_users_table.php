<?php

use App\Models\DeviceAuthAndHierarchyElementRole;
use App\Models\OrganizationElement;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_element_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrganizationElement::class, 'organization_element_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', [DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE, DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE]);
            $table->timestamps();

            $table->unique(['organization_element_id', 'user_id', 'type'], 'unique_type_organization_element_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_element_users');
    }
};
