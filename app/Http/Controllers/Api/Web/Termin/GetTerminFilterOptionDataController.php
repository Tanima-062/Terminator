<?php

namespace App\Http\Controllers\Api\Web\Termin;

use App\Models\User;
use OpenSearch\Client;
use App\Models\Campaign;
use App\Models\BrokerUser;
use Illuminate\Http\Request;
use App\Models\ContactDataRecord;
use App\Models\OrganizationElement;
use App\Http\Controllers\Controller;
use App\Traits\ContactDataRecords\DropdownOption;

class GetTerminFilterOptionDataController extends Controller
{
    use DropdownOption;


    private $indexName = ContactDataRecord::OPENSEARCH_INDEX_NAME;

    public function __construct(protected Client $client)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (request('column') ==   'intermediaries') {
            return $this->intermediariesOptions($request);
        }
        if (request('column') ==   'cantons') {
            return $this->getCantonOptions($request);
        }
        if (request('column') ==   'correspondence_languages') {
            return $this->getCorrespondenceLanguageOptions($request);
        }
        if (request('column') ==   'other_languages') {
            return $this->getOtherLanguageOptions($request);
        }
        if (request('column') ==   'contact_record_status') {
            return $this->getContactRecordStatusOptions($request);
        }
    }





    /**
     * Get canton options data
     *
     * @param Request $request
     * @return void
     */
    protected function intermediariesOptions(Request $request){
        info('get intermediaries');

        $search = $this->getUniqueAggrations($request, 'allocation.broker_user_id');

        // info($search);
        if(count($search)){
            $user_ids = BrokerUser::whereIn('id', $search)->where('role', BrokerUser::INTERMEDIARY)->pluck('user_id')->toArray();

            // info($user_ids);

            $user = User::query()->select('id as value', 'full_name as label')
                ->whereIn('id', $user_ids)
                ->orderBy('full_name', 'ASC')
                ->paginate(request('per_page', 10));

            return response($user);

        }else {
            return response([
                'data'  =>  [],
                'meta'  =>  customPagination(0, request('per_page', 10))
            ]);
        }
    }

    /**
     * Get canton options data
     *
     * @param Request $request
     * @return void
     */
    protected function getCantonOptions(Request $request){

        $search =  $this->getUniqueAggrations($request, 'canton', true);

        $data = [];
        foreach($search as $item){
            $data[] = [
                'value'     =>  $item,
                'label'     =>  $item
            ];
        }

        return response([
            'data'  =>  $data,
            'meta'  =>  customPagination($total = count($data), $total)
        ]);
    }


    /**
     * Get correspondence language options data
     *
     * @param Request $request
     * @return void
     */
    protected function getCorrespondenceLanguageOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'correspondence_language', true);

        $all_lanaugaes =  getLanguageNameByUser();

        $lanaugaes = [];
        foreach($search as $item){
            $lanaugaes[] = [
                'value'     =>  $item,
                'label'     => $all_lanaugaes[$item]
            ];
        }

        $sorted =  collect($lanaugaes)->sortBy('label')->values();

        return response([
            'data'  =>  $sorted,
            'meta'  =>  customPagination($total = count($sorted), $total)
        ]);
    }
    /**
     * Get other languages options data
     *
     * @param Request $request
     * @return void
     */
    protected function getOtherLanguageOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'other_languages', true);

        // return $search;

        $all_lanaugaes =  getLanguageNameByUser();

        $lanaugaes = [];
        foreach($search as $item){
            $lanaugaes[] = [
                'value'     =>  $item,
                'label'     => $all_lanaugaes[$item]
            ];
        }

        $sorted =  collect($lanaugaes)->sortBy('label')->values();

        return response([
            'data'  =>  $sorted,
            'meta'  =>  customPagination($total = count($sorted), $total)
        ]);
    }


    /**
     * Get campaign options data
     *
     * @param Request $request
     * @return void
     */
    protected function getContactRecordStatusOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'contact_record_status', true);
        $data = [];
        foreach($search as $item){
            $data[] = [
                'value'     =>  $item,
                'label'     =>  $item
            ];
        }

        return response([
            'data'  =>  $data,
            'meta'  =>  customPagination($total = count($data), $total)
        ]);
    }



    /**
     * Get unique values with aggregation
     *
     * @param Request $request
     * @param string $field
     * @return array
     */
    protected function getUniqueAggrations(Request $request, string $field, $keyword = false): array{
        $column = $keyword ? $field.'.keyword' : $field;
        $customer_company_id = auth()->user()->customer_company_id;

        $broker_user = BrokerUser::where('user_id', auth()->user()->id)->first();
        $broker_users = BrokerUser::where('broker_id', $broker_user->broker_id)->pluck('id')->toArray();


        $search =  $this->client->search([
            'index' => $this->indexName,
            'body' => [
                'size' => 0,
                'from' => 0,
                '_source'=> false,
                'track_total_hits'=> false,
                "query" => [
                    "bool"=> [
                        "must"=> [
                            [
                                'term'=>[
                                    'customer_company_id'=> $customer_company_id
                                ]
                            ],
                            [
                                'terms'=>[
                                    'category.keyword'=>  [
                                        'termination_appointment',
                                    ]
                                ]
                            ],
                            [
                                "bool"  => [
                                    "should" => [
                                        [
                                            'term'=>[
                                                "allocation.broker_id" => $broker_user->broker_id
                                            ]
                                        ],
                                        [
                                            'term'=>[
                                                "allocation.broker_user_id" => $broker_user->id
                                            ]
                                        ],
                                        [
                                            'terms'=>[
                                                "allocation.broker_user_id" => $broker_users
                                            ]
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs'  =>  [
                    'unique_counts' =>  [
                        'cardinality'   => [
                            'field' =>  $column
                        ]
                    ],
                    'keys'  => [
                        'composite'=> [
                            'sources'=> [
                                [
                                    $field => [
                                        'terms' => [
                                            'field' =>   $column
                                        ]
                                    ]
                                ]
                            ],
                            // 'size'=> $per_page,
                            'size'=> 10000,
                            // 'after'=> [
                            //     $field => ''
                            // ]
                        ]
                    ]
                ]
                // 'sort'=>  [
                //     'id'=> 'asc'
                // ],
            ]
        ]);

        if(isset($search['aggregations']) && isset($search['aggregations']['keys']) &&  isset($search['aggregations']['keys']['buckets'])){
            $buckets = $search['aggregations']['keys']['buckets'];


            $ids =  $this->getValueFromAggregationData($field, $buckets);

            return $ids;
        }

        return [];
    }





    /**
     * Get value from aggregation query
     *
     * @param string $key
     * @param array $aggregations
     * @return array
     */
    public function getValueFromAggregationData(string $key, array $aggregations = []): array
    {
        $data = [];

        foreach($aggregations as $item){
            $data[] = $item['key'][$key];
        }
        return $data;
    }
}
