<?php

namespace App\Http\Requests\HierarchyElement;

use App\Models\CustomerCompany;
use App\Models\HierarchyElement;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name"                            => ['required', 'string', 'max:40'],
            'customer_company_id'             => ['required', 'exists:customer_companies,id'],
            "responsible_role"                => ['required_with:hierarchy_level'],
            "hierarchy_level"                 => ['nullable', 'integer', 'min:1', 'max:10', "unique:hierarchy_elements,hierarchy_level,NULL,id,customer_company_id,{$this->customer_company_id}"],
            "responsible_role.*"              => ['in:Leader,Manager,Quality Controller,Call Agent'],
            "direct_subordinated_role"        => ['required_with:hierarchy_level'],
            "direct_subordinated_role.*"      => ['in:Leader,Manager,Quality Controller,Call Agent'],
            "status"                          => ['required', "in:" . HierarchyElement::STATUS_ACTIVE, "," . HierarchyElement::STATUS_INACTIVE]
        ];
    }

    public function withValidator($validator)
    {
        return $validator->after(function ($validator) {
            if ($this->hierarchy_level) {
                $customer_company = CustomerCompany::with("hierarchyElements.directSubordinateRoles")->where('id', $this->customer_company_id)->first();
                $existing_roles = $customer_company->hierarchyElements->where('id', '!=', $this->organization_element->id)->where('hierarchy_level', '!=', null)->pluck('directSubordinateRoles')->flatten()->pluck('role');
                $diffs = $existing_roles->diff($this->direct_subordinated_role);
                if ($diffs->count() !== $existing_roles->count()) {
                    $validator->errors()->add('direct_subordinated_role', 'Direct Subordinated Role Must Be unique if hierarchy level is not None');
                };
            }
        });
    }
}
