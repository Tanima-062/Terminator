<?php

use App\Models\CompanyRole;
use App\Models\DeviceAuthAndHierarchyElementRole;
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
        Schema::create('device_auth_and_hierarchy_element_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roleable_id');
            $table->string('roleable_type');
            $table->foreignIdFor(CompanyRole::class, 'company_role_id');
            $table->enum('role_type', [DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE, DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE])->nullable();
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
        Schema::dropIfExists('device_auth_and_hierarchy_element_roles');
    }
};
