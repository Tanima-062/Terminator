<?php

namespace App\Http\Requests\CustomerCompany;

use App\Models\HierarchyElement;
use App\Traits\HasPermission;
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
        return $this->user('sanctum')->canAccess('customer-companye:edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name"                                                      => ['required', 'max:50',],
            "alias_name"                                                => ['required', 'max:50',],
            "street_name"                                               => ['required', 'max:30', 'string'],
            "street_number"                                             => ['required', 'max:30', 'string'],
            "zip_code"                                                  => ['required', 'max:4', 'min:1', 'string'],
            "city"                                                      => ['required', 'max:30', 'string'],
            "country_iso_code"                                          => ['required', 'max:10'],
            "contact_person_first_name"                                 => ['required', 'max:30', 'regex:/^[\d\p{L}\p{M}\p{Zs}\-]+$/u'],
            "contact_person_last_name"                                  => ['required', 'max:30', 'regex:/^[\d\p{L}\p{M}\p{Zs}\-]+$/u'],
            "contact_person_email"                                      => ['required_without:contact_person_phone', 'nullable', 'email:filter'],
            "contact_person_phone_iso_code"                             => ['required_with:contact_person_phone', 'exclude_without:contact_person_phone', 'max:10'],
            "contact_person_phone"                                      => ['required_without:contact_person_email', 'nullable', 'digits_between:4,15'],
            "device_authentication_required"                            => ['required', 'boolean'],
            "affected_user_roles"                                       => ['required_if:device_authentication_required,1', 'exclude_if:device_authentication_required,0'],
            "affected_user_roles.*"                                     => ['exists:company_roles,id'],
            "auto_logout_time"                                          => ['integer', 'min:60', 'max:6000'],
            "hierarchy_elements_required"                               => ['required', 'boolean'],
            "hierarchy_elements"                                        => ['required_if:hierarchy_elements_required,1', 'exclude_if:hierarchy_elements_required,0'],
            "hierarchy_elements.*"                                      => ['array'],
            "hierarchy_elements.*.hierarchy_level"                      => ['nullable', 'integer', 'min:0', 'max:10'],
            "hierarchy_elements.*.name"                                 => ['required', 'string', 'max:40'],
            "hierarchy_elements.*.id"                                   => ['sometimes', 'exists:hierarchy_elements,id'],
            "hierarchy_elements.*.status"                               => ['sometimes', "in:" . HierarchyElement::STATUS_ACTIVE . "," . HierarchyElement::STATUS_INACTIVE,],
            "hierarchy_elements.*.responsible_role"                     => ['required_with:hierarchy_elements.*.hierarchy_level'],
            "hierarchy_elements.*.responsible_role.*"                   => ['exists:company_roles,id'],
            "hierarchy_elements.*.direct_subordinated_role"             => ['required_with:hierarchy_elements.*.hierarchy_level'],
            "hierarchy_elements.*.direct_subordinated_role.*"           => ['exists:company_roles,id'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hierarchy_elements_required) {
                $roles = collect($this->hierarchy_elements)->filter(fn ($element) => $element['hierarchy_level'] !== null)->pluck('direct_subordinated_role')->flatten();
                $unique_roles = $roles->unique();
                if ($roles->count() !== $unique_roles->count()) {
                    $validator->errors()->add("hierarchy_elements.*.direct_subordinated_role.*", "Duplicate direct subordinate roles entry");
                }
            }
        });
    }
}
