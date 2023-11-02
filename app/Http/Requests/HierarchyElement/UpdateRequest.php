<?php

namespace App\Http\Requests\HierarchyElement;

use App\Models\CustomerCompany;
use App\Models\HierarchyElement;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            "hierarchy_level"                 => ['nullable', 'integer', 'min:1', 'max:10', "unique:hierarchy_elements,hierarchy_level,{$this->hierarchy_element->id},id,customer_company_id,{$this->hierarchy_element->customer_company_id}"],
            "name"                            => ['required', 'string', 'max:40'],
            "responsible_role"                => ['required_with:hierarchy_level'],
            "responsible_role.*"              => ['exists:company_roles,id'],
            "direct_subordinated_role"        => ['required_with:hierarchy_level'],
            "direct_subordinated_role.*"      => ['exists:company_roles,id'],
            "status"                          => ['required', "in:" . HierarchyElement::STATUS_ACTIVE . "," . HierarchyElement::STATUS_INACTIVE]
        ];
    }

    public function withValidator($validator)
    {
        return $validator->after(function ($validator) {
            if ($this->hierarchy_level) {
                $customer_company = CustomerCompany::with("hierarchyElements.directSubordinateRoles")->where('id', $this->hierarchy_element->customer_company_id)->first();
                $existing_roles = $customer_company->hierarchyElements->where('id', '!=', $this->hierarchy_element->id)->where('hierarchy_level', '!=', null)->pluck('directSubordinateRoles')->flatten()->pluck('role');
                $diffs = $existing_roles->diff($this->direct_subordinated_role);
                if ($diffs->count() !== $existing_roles->count()) {
                    $validator->errors()->add('direct_subordinated_role', 'Direct Subordinated Role Must Be unique if hierarchy level is not None');
                };
            }
        });
    }
}
