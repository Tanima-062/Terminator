<?php
namespace App\Traits;

use App\Contracts\CompanyWisePrefixIDContract;

trait CompanyWisePrefixID{
    public static function boot(){
        parent::boot();

        static::creating(function($model){
            if($model instanceof CompanyWisePrefixIDContract){
                $model->prefix_id = self::generatePrefixId($model);
            }
        });
    }


    /**
     * Generate prefix_id by company
     *
     * @param App\Model $model
     * @param string $column
     * @return string
     */
    protected static function generatePrefixId($model, string $column = 'customer_company_id'): string{
        $last_id = 1;

        if($last_item = self::where($column, $model->customer_company_id)->orderBy('id', 'DESC')->first()){
            if($last_item->prefix_id && preg_match("%([1-9]\d?.*?)$%", $last_item->prefix_id, $matches)){
                $last_id = ((int) $matches[1]) + 1;
            }
        }

        $prefix =  sprintf("%s%05d", self::getPrefix(), $last_id);

        // $model->prefix_id = $prefix;
        return $prefix;
    }


     /**
     * Generate prefix_id by company
     *
     * @param int $customer_company_id
     * @param string $column
     * @return string
     */
    protected static function generatePrefixIdByCompanyId(int $customer_company_id, string $column = 'customer_company_id'): string{
        $last_id = 1;

        if($last_item = self::where($column, $customer_company_id)->orderBy('id', 'DESC')->first()){
            if($last_item->prefix_id && preg_match("%([1-9]\d?.*?)$%", $last_item->prefix_id, $matches)){
                $last_id = ((int) $matches[1]) + 1;
            }
        }

        return sprintf("%s%05d", self::getPrefix(), $last_id);
    }
}
