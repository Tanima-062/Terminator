<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords\Leads;

use Illuminate\Http\Request;
use App\Models\ContactDataRecord;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\OrganizationElement;
use App\Models\User;
use App\Traits\ContactDataRecords\DropdownOption;
use OpenSearch\Client;

class GetContactDataRecordLeadsFilterOptionDataController extends Controller
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
        if (request('column') == 'internal_users') {
            return $this->getInternalUserOptions($request);
        }

        if (request('column') == 'sources') {
            return $this->getSourceOptions($request);
        }
        if (strtolower(request('column')) ==   'variablea') {
            return $this->getOrganizationElementOptions($request);
        }
        if (request('column') ==   'cantons') {
            return $this->getCantonOptions($request);
        }

        if (request('column') ==   'regions') {
            return $this->getRegionOptions($request);
        }
        if (request('column') ==   'correspondence_languages') {
            return $this->getCorrespondenceLanguageOptions($request);
        }
        if (request('column') ==   'other_languages') {
            return $this->getOtherLanguageOptions($request);
        }
        if (request('column') ==   'campaigns') {
            return $this->getCampaignOptions($request);
        }
        if (request('column') ==   'saves') {
            return $this->getSaveOptions($request);
        }
        if (request('column') ==   'health_insurances') {
            return $this->getHealthInsuranceOptions($request);
        }
        if (request('column') ==   'third_pillers') {
            return $this->getThirdPillerOptions($request);
        }
        if (request('column') ==   'contact_desireds') {
            return $this->getContactDesiredOptions($request);
        }
        if (request('column') ==   'leads') {
            return $this->getLeadOptions($request);
        }
        if (request('column') ==   'feedbacks') {
            return $this->getFeedbackOptions($request);
        }
        if (request('column') ==   'duplicates') {
            return $this->getDuplicateOptions($request);
        }
        if (request('column') ==   'contact_record_status') {
            return $this->getContactRecordStatusOptions($request);
        }
    }



    /**
     * Get source options data
     *
     * @param Request $request
     * @return void
     */
    protected function getSourceOptions(Request $request){
        return response([
            'data'  =>  self::$source_lists,
            'meta'  =>  customPagination($total = count(self::$source_lists), $total)
        ]);
    }



    /**
     * Get internal users options data
     *
     * @param Request $request
     * @return void
     */
    protected function getInternalUserOptions(Request $request){
        $search = $this->getUniqueAggrations($request, 'allocation.user_id');

        if(count($search)){
            $user = User::query()->select('id as value', 'full_name as label')
                ->whereIn('id', $search)
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
     * Get organization elements options data
     *
     * @param Request $request
     * @return void
     */
    protected function getOrganizationElementOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'allocation.organization_element_id');

         if(count($search)){
            $data = OrganizationElement::query()->select('id as value', 'name as label')
                ->whereIn('id', $search)
                ->orderBy('name', 'ASC')
                ->paginate(request('per_page', 10));

            return response($data);
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
     * Get region options data
     *
     * @param Request $request
     * @return void
     */
    protected function getRegionOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'region', true);

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
    protected function getCampaignOptions(Request $request){
        return response([
            'data'  =>  $campaigns = Campaign::query()->select('id as value', 'name as label')->get(),
            'meta'  =>  customPagination($total = count($campaigns), $total)
        ]);
    }

    /**
     * Get campaign options data
     *
     * @param Request $request
     * @return void
     */
    protected function getSaveOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'save', true);

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
     * Get campaign options data
     *
     * @param Request $request
     * @return void
     */
    protected function getHealthInsuranceOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'health_insurance', true);

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
     * Get campaign options data
     *
     * @param Request $request
     * @return void
     */
    protected function getThirdPillerOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'third_piller', true);

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
     * Get campaign options data
     *
     * @param Request $request
     * @return void
     */
    protected function getContactDesiredOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'contact_desired', true);

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
     * Get campaign options data
     *
     * @param Request $request
     * @return void
     */
    protected function getLeadOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'lead', true);

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
     * Get campaign options data
     *
     * @param Request $request
     * @return void
     */
    protected function getFeedbackOptions(Request $request){
        $search =  $this->getUniqueAggrations($request, 'feedbacks', true);
        //will implement later
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
     * Get campaign options data
     *
     * @param Request $request
     * @return void
     */
    protected function getDuplicateOptions(Request $request){
        //will implement later
        $data = [
            [
                'value'     =>  'Duplicate',
                'label'     =>  'Duplicate'
            ],
            [
                'value'     =>  'Check Duplicate',
                'label'     =>  'Check Duplicate'
            ],
            [
                'value'     =>  'No Duplicate',
                'label'     =>  'No Duplicate'
            ],
        ];

        return response([
            'data'  =>  $data,
            'meta'  =>  customPagination($total = count($data), $total)
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
                                        'termination_lead',
                                        'future_lead',
                                        'lead_again',
                                        'lead',
                                    ]
                                ]
                            ],
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
