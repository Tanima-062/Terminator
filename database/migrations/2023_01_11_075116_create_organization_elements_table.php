<?php

use App\Models\CustomerCompany;
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
        Schema::create('organization_elements', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_id')->index();
            $table->foreignIdFor(HierarchyElement::class, 'type_id');
            $table->string('name', 40)->index();
            $table->enum('status', [OrganizationElement::STATUS_ACTIVE, OrganizationElement::STATUS_INACTIVE])->default(OrganizationElement::STATUS_ACTIVE)->index();
            $table->foreignIdFor(CustomerCompany::class, 'customer_company_id');
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
        Schema::dropIfExists('organization_elements');
    }
};
