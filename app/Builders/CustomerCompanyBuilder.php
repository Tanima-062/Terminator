<?php

namespace App\Builders;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


class CustomerCompanyBuilder extends Builder {



    /**
     * Order By columns
     *
     * @return self
     */
    public function orderByColumn(): self
    {
        if(request('order_by') && in_array(request('order_by'), ['prefix_id', 'name', 'alias_name', 'contact_person_first_name', 'status',])){
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

        return  $this->whereIn('status', $statusArray);
    }



    /**
     * Filter by company name
     *
     * @return self
     */
    public function filterByCompanyName(): self
    {
        $customer_companies = explode(',', request('customer_companies'));

        return $this->whereIn('id', $customer_companies);
    }



    /**
     * Filter by company location/city
     *
     * @return self
     */
    public function filterByLocation(): self
    {
        $company_locations = explode(',', request('company_locations'));

        return $this->whereIn('city', $company_locations);
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

        return $this->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date);
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

            $q->where('prefix_id', 'LIKE', "$search%")
                ->orWhere('name', 'LIKE', "$search%")
                ->orWhere('city', 'LIKE', "$search%")
                ->orWhere('contact_person_first_name', 'LIKE', "$search%")
                ->orWhere('contact_person_last_name', 'LIKE', "$search%")
                ->orWhere('contact_person_name', 'LIKE', "$search%")
                ->orWhere('contact_person_email', 'LIKE', "$search%")
                ->orWhere('contact_person_full_phone_number', 'LIKE', "$search%")
            ;
        });
    }
}
