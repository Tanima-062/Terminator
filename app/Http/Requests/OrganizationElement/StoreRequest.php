<?php

namespace App\Http\Requests\OrganizationElement;

use App\Models\HierarchyElement;
use App\Models\OrganizationElement;
use App\Traits\HasPermission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    use HasPermission;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user('sanctum')->can('create', OrganizationElement::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $customer_company_id = $this->user('sanctum')->customerCompanyAdmin->customer_company_id;
        return [
            'type_id'                                                   => ['required', "exists:hierarchy_elements,id,customer_company_id,$customer_company_id"],
            'name'                                                      => ['required', 'string', 'max:40'],
            'responsible_users'                                         => ['array'],
            'responsible_users.*'                                       => ['integer'],
            'subordinate_users'                                         => ['array'],
            'subordinate_users.*'                                       => ['integer'],
            'parent_hierarchy'                                          => ['array'],
            'parent_hierarchy.*'                                        => ['array:organization_element_id,hierarchy_id'],
            'parent_hierarchy.*.organization_element_id'                => ['nullable', 'integer'],
            'parent_hierarchy.*.hierarchy_id'                           => ['required', 'integer', "exists:hierarchy_elements,id,customer_company_id,$customer_company_id"],
        ];
    }


    public function withValidator($validator)
    {
        return $validator->after(function ($validator) {
            $hierarchy_type = HierarchyElement::find($this->type_id);
            $parent_hierarchies = HierarchyElement::with(['organizationElements'])
                ->withCount('organizationElements')
                ->where('customer_company_id', $hierarchy_type->customer_company_id)
                ->where(function ($q) use ($hierarchy_type) {
                    if ($hierarchy_type->hierarchy_level == null) return $q->where('hierarchy_level', null);
                    return $q->where('hierarchy_level', '<', $hierarchy_type->hierarchy_level);
                })
                ->where('status', HierarchyElement::STATUS_ACTIVE)
                ->get();

            if (count($this->parent_hierarchy) < $parent_hierarchies->whereNotNull('hierarchy_level')->where('organization_elements_count', '>', 0)->count()) {
                $validator->errors()->add("parent_hierarchy", "organization_element_id required");
            }

            $selected_organization_elements = OrganizationElement::whereIn('id', collect($this->parent_hierarchy)->pluck('organization_element_id'))->get();
            collect($this->parent_hierarchy)->each(function ($element, $index) use ($selected_organization_elements, $parent_hierarchies, $hierarchy_type, $validator) {
                $organization_element = $selected_organization_elements->where('id', $element['organization_element_id'])->first();

                if (!$organization_element && $hierarchy_type->hierarchy_level !== null && $parent_hierarchies->where('id', $element['hierarchy_id'])->first()->organizationElements->count()) {
                    $validator->errors()->add("parent_hierarchy.$index", "organization_element_id required");
                }

                if ($organization_element && !$parent_hierarchies->where('id', $organization_element->type_id)->count()) {
                    $validator->errors()->add("parent_hierarchy.$index", "organization_element_id is invalid");
                }
            });
        });
    }
}
