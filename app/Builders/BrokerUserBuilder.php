<?php

namespace App\Builders;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class BrokerUserBuilder extends Builder {



    /**
     * Order By columns
     *
     * @return self
     */
    public function orderByColumn(): self
    {
        if(request('order_by') && in_array(request('order_by'), ['prefix_id', 'role'])){
            return $this->orderBy('broker_users.'.request('order_by'), request('direction', 'DESC'));
        }
        if(request('order_by') == 'broker'){
            return $this->orderBy('brokers.name', request('direction', 'DESC'));
        }
        if(request('order_by') == 'correspondence_language'){
            $query = getOrderByLanguageNameByUser(request('direction', 'asc'));

            return $this->orderBy(DB::raw($query));
        }

        if(request('order_by') == 'name'){
            return $this->orderBy('users.full_name', request('direction', 'DESC'));
        }
        if(request('order_by') == 'status'){
            return $this->orderBy('users.status', request('direction', 'DESC'));
        }
        if(request('order_by') == 'created_at'){
            return $this->orderBy('broker_users.created_at', request('direction', 'DESC'))->orderBy('broker_users.id', request('direction', 'DESC'));
        }

        return $this->orderBy('broker_users.id', 'DESC');
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
     * Filter by user name
     *
     * @return self
     */

    public function filterByUserName(): self
    {
        $users = explode(',', request('users'));

        return $this->whereIn('users.id', $users);
    }



    /**
     * Filter by company location/city
     *
     * @return self
     */
    public function filterByRole(): self
    {
        $roles = explode(',', request('roles'));

        return $this->whereIn('broker_users.role', $roles);
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

        return $this->where('broker_users.created_at', '>=', $start_date)
            ->where('broker_users.created_at', '<=', $end_date);
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

            $q->Where('broker_users.full_phone_number', 'LIKE', "$search%")
                ->orWhere('users.full_name','LIKE', "$search%")
                ->orWhere('users.first_name','LIKE', "$search%")
                ->orWhere('users.last_name','LIKE', "$search%")
                ->orWhere('users.email', 'LIKE', "$search%")
            ;
        });
    }
}
