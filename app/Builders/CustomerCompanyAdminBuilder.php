<?php

namespace App\Builders;

use App\Models\CustomerCompany;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


class CustomerCompanyAdminBuilder extends Builder {



    /**
     * Order By columns
     *
     * @return self
     */
    public function orderByColumn(): self
    {
        if(request('order_by') && in_array(request('order_by'), ['full_name', 'email', 'status',])){
            return $this->orderBy('users.'.request('order_by'), request('direction', 'DESC'));
        }
        if(request('order_by') == 'customer_company'){
            return $this->orderBy('customer_companies.name', request('direction', 'DESC'));
        }
        else if(request('order_by') && in_array(request('order_by'), ['full_phone_number',])){
            return $this->orderBy(request('order_by'), request('direction', 'DESC'));
        }
        if(request('order_by') == 'created_at'){
            return $this->orderBy('created_at', request('direction', 'DESC'))->orderBy('id', request('direction', 'DESC'));
        }

        return $this->orderBy('id', 'DESC');
    }




    /**
     * Filter by status
     *
     * @return self
     */
    public function filterByStatus(): self
    {
        $statusArray = explode(',', request('status'));

        return  $this->whereIn('users.status', $statusArray);
    }



    /**
     * Filter by customer company name
     *
     * @return self
     */
    public function filterByCustomerCompanyName(): self
    {
        $customer_companies = explode(',', request('customer_companies'));

        return $this->whereIn('customer_company_admins.customer_company_id', $customer_companies);
    }
    /**
     * Filter by user name
     *
     * @return self
     */
    public function filterByUserName(): self
    {
        $names = explode(',', request('names'));

        return $this->whereIn('users.full_name', $names);
    }



    /**
     * Filter between created_at
     *
     * @return self
     */
    public function filterByBetweenCreatedAt(): self
    {
        $start_date = Carbon::parse(request('start_date'))->startOfDay();
        $end_date = Carbon::parse(request('end_date'))->endOfDay();

        return $this->where('customer_company_admins.created_at', '>=', $start_date)
            ->where('customer_company_admins.created_at', '<=', $end_date);
    }



    /**
     * Search by prefix_id, name, city, contact person first_name, last_name, email, phone number
     *
     * @return self
     */
    public function overviewSearch(): self
    {
        return $this->where(function($q){
            $search = request('search');

            $q
            ->whereIn('user_id', User::query()
                ->where('first_name', 'LIKE', "$search%")
                ->orWhere('last_name', 'LIKE', "$search%")
                ->orWhere('full_name', 'LIKE', "$search%")
                ->orWhere('email', 'LIKE', "$search%")
                ->pluck('id')
            )
            ->orWhereIn('customer_company_admins.customer_company_id', CustomerCompany::query()
                ->where('name', 'LIKE', "$search%")
                ->pluck('id')
            )
            ->orWhere('customer_company_admins.phone_number', 'LIKE', "%$search%")
            ->orWhere('customer_company_admins.full_phone_number', 'LIKE', "%$search%")
            ;
        });
    }
}
