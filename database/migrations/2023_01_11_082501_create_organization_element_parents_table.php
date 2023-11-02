<?php

use App\Models\DeviceAuthAndHierarchyElementRole;
use App\Models\HierarchyElement;
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
        Schema::create('organization_element_parents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrganizationElement::class, 'organization_element_id');
            $table->foreignIdFor(HierarchyElement::class, 'parent_organization_element_id');
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
        Schema::dropIfExists('organization_element_parent');
    }
};
