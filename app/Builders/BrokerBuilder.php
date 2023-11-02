<?php

namespace App\Builders;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


class BrokerBuilder extends Builder {



    /**
     * Order By columns
     *
     * @return self
     */
    public function orderByColumn(): self
    {
        if(request('order_by') && in_array(request('order_by'), ['prefix_id', 'name', 'contact_person_first_name', 'status',])){
            return $this->orderBy('brokers.'.request('order_by'), request('direction', 'DESC'));
        }
        if(request('order_by') == 'customer_company'){
            return $this->orderBy('customer_companies.name', request('direction', 'DESC'));
        }
        if(request('order_by') == 'created_at'){
            return $this->orderBy('brokers.created_at', request('direction', 'DESC'))->orderBy('brokers.id', request('direction', 'DESC'));
        }

        return $this->orderBy('brokers.id', 'DESC');
    }




    /**
     * Filter by status
     *
     * @return self
     */
    public function filterByStatus(): self
    {
        $statusArray = explode(',', request('status'));

        return  $this->whereIn('brokers.status', $statusArray);
    }



    /**
     * Filter by company name
     *
     * @return self
     */
    public function filterByBrokerName(): self
    {
        $brokers = explode(',', request('brokers'));

        return $this->whereIn('brokers.id', $brokers);
    }



    /**
     * Filter by company location/city
     *
     * @return self
     */
    public function filterByLocation(): self
    {
        $broker_locations = explode(',', request('broker_locations'));

        return $this->whereIn('brokers.city', $broker_locations);
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

        return $this->where('brokers.created_at', '>=', $start_date)
            ->where('brokers.created_at', '<=', $end_date);
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

            $q->Where('brokers.name', 'LIKE', "$search%")
                ->orWhere('brokers.prefix_id', 'LIKE', "$search%")
                ->orWhere('brokers.city', 'LIKE', "$search%")
                ->orWhere('customer_companies.name','LIKE', "$search%")
                ->orWhere('brokers.contact_person_first_name', 'LIKE', "$search%")
                ->orWhere('brokers.contact_person_last_name', 'LIKE', "$search%")
                ->orWhere('brokers.contact_person_email', 'LIKE', "$search%")
                ->orWhere('brokers.contact_person_full_phone_number', 'LIKE', "$search%")
            ;
        });
    }
}
