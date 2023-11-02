<?php

namespace App\Http\Controllers;

use App\Models\InternalUser;
use App\Models\OrganizationElement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GetNextPrefixId extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $modelName)
    {
        switch ($modelName) {
            case "OrganizationElement":
                abort_if(!$request->user()->can('create', OrganizationElement::class), 403, "You are unautorized to access this model");
                return getNextPrefixId($modelName, fn ($q) => $q->where('customer_company_id', $request->user()->customer_company_id));
            case "InternalUser":
                //abort_if(!$request->user()->can('create', InternalUser::class), 401, "You are unautorized to access this model");
                return getNextPrefixId($modelName, fn ($q)
                => $q->where('customer_company_id', $request->user()->customer_company_id));

            case "ContactDataRecordAppointment":
                if (!$request->has('contact_data_record_id')) throw ValidationException::withMessages(['contact_data_record_id' => 'contact_data_record_id is required']);
                return getNextPrefixId($modelName, fn ($q) => $q->where('contact_data_record_id', $request->contact_data_record_id));
            default:
                return getNextPrefixId($modelName);
        }
    }
}
