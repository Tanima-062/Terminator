<?php

use App\Models\HierarchyElement;
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
        Schema::create('hierarchy_elements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->unsignedInteger('hierarchy_level')->nullable()->index();
            $table->foreignId('customer_company_id')->constrained('customer_companies');
            $table->enum('status', [HierarchyElement::STATUS_ACTIVE, HierarchyElement::STATUS_INACTIVE])->default(HierarchyElement::STATUS_ACTIVE)->index();
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
        Schema::dropIfExists('hierarchy_elements');
    }
};
