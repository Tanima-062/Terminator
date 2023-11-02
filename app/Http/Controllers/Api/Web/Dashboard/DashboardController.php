<?php

namespace App\Http\Controllers\Api\Web\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Broker\StoreRequest;
use App\Models\User;
use App\Models\ContactDataRecord;
use App\Models\WorkflowSetting;
use App\Models\ContactDataRecordHistory;
use App\Models\InternalUser;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user = Auth::user();


        $condition_label = [
            ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION,
            ContactDataRecordHistory::LEAD_QUALITY_CHECK,
            ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL,
            ContactDataRecordHistory::APPOINTMENT_CONTROL_TASK_EDITED,

            ContactDataRecordHistory::DATA_VERIFICATION_UPDATE,
            ContactDataRecordHistory::LEAD_CONTROL_TASK_EDITED,
            ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK,
            ContactDataRecordHistory::APPOINTMENT_REMINDER,
        ];

        if ($user->type == 'internal_user') {
            $internalUser = InternalUser::with('companyRole')
                ->where('user_id', $user->id)
                ->first();
            $request->merge(['internal_users' => $internalUser->id]);
            if ($internalUser->companyRole->name == 'Call agent') {
                array_push($condition_label, ContactDataRecordHistory::STATUS_POSITIVE_COMPLETED);
            }
        }
        if ($user->type == 'broker_user') {
             $condition_label = [
                ContactDataRecordHistory::STATUS_NEW,
                ContactDataRecordHistory::STATUS_APPOINMENT_NOT_TAKE_PLACE,
                ContactDataRecordHistory::STATUS_OPEN,
                ContactDataRecordHistory::STATUS_POSITIVE_COMPLETED,
                ContactDataRecordHistory::STATUS_NEGATIVE_COMPLETED,
             ];
        }


        $contact_data =  ContactDataRecord::query()
            ->select('contact_data_records.id')
            ->leftJoin('contact_data_record_allocates', 'contact_data_records.id', '=', 'contact_data_record_allocates.contact_data_record_id')
            ->when($request->internal_users, function ($query) use ($request) {
                // Filter the records by internal user id
                $query->whereIn('internal_user_id', explode(",", $request->internal_users));
            })
            ->when($request->broker_users, function ($query) use ($request) {
                // Filter the records by internal user id
                $query->whereIn('broker_user_id', explode(",", $request->broker_users));
            })
            ->where('contact_data_records.customer_company_id', Auth::user()->customer_company_id)->get();

        $total_contact_data = $contact_data->count();

        $contact_data_id =  $contact_data->pluck('id');
        $contact_data_record_history = ContactDataRecordHistory::query()->whereIn('contact_data_record_id', $contact_data_id)->when(request('start_date') && request('end_date'), function ($q) use ($request) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
            return $q->whereBetween('created_at', [$start, $end]);
        });


        $performance_chart_data = $this->getPerformanceCountPieCartData($request, $contact_data_id, $condition_label);
        $cost_chart_data = $this->getCostCountPieCartData($request, $contact_data_id, $condition_label);
        $success_chart_data = $this->getSuccessCountPieCartData($request, $contact_data_id, $condition_label);
        $profit_chart_data = $this->getProfitCountPieCartData($request, $contact_data_id, $condition_label);

        $data = [
            'total_contact_data' => $total_contact_data,
            'total_work_steps' =>  $contact_data_record_history->count(),
            'performance_chart_data' => $performance_chart_data,

            'cost_chart_data' => $cost_chart_data,
            'total_cost_data' => count($cost_chart_data['cost_count_per_condition']) == 0 ? 0 : array_sum($cost_chart_data['cost_count_per_condition']),

            'success_chart_data' => $success_chart_data,
            'total_success_data' => count($success_chart_data['success_count_per_condition']) == 0 ? 0 : ceil((array_sum($success_chart_data['success_count_per_condition']) * 100) / $total_contact_data),

            'profit_chart_data' => $profit_chart_data,
            'total_profit_data' => count($profit_chart_data['profit_count_per_condition']) == 0 ? 0 : array_sum($profit_chart_data['profit_count_per_condition'])
        ];
        return $data;
    }

    /**
     * Performance Count Pie Chart Data And Labels
     *
     * @return Array
     */
    private function getPerformanceCountPieCartData(Request $request, $contact_data_ids, $performance_condition_label)
    {

        $performance_count_per_condition = collect([]);


        foreach ($performance_condition_label as $condition) {
            $contact_history = ContactDataRecordHistory::query()
                ->whereIn('contact_data_record_id', $contact_data_ids)->when(request('start_date') && request('end_date'), function ($q) use ($request) {
                    $start = Carbon::parse(request('start_date'))->startOfDay();
                    $end = Carbon::parse(request('end_date'))->endOfDay();
                    return $q->whereBetween('created_at', [$start, $end]);
                });

            if ($condition == ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION) {
                $count = $contact_history->where('action', ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#BA27FF', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::LEAD_QUALITY_CHECK) {
                $count = $contact_history->where('action', ContactDataRecordHistory::LEAD_QUALITY_CHECK)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#5B7DFF', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#323232', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_CONTROL_TASK_EDITED) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_CONTROL_TASK_EDITED)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#FFC000', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::DATA_VERIFICATION_UPDATE) {
                $count = $contact_history->where('action', ContactDataRecordHistory::DATA_VERIFICATION_UPDATE)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#26CD4A', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::LEAD_CONTROL_TASK_EDITED) {
                $count = $contact_history->where('action', ContactDataRecordHistory::LEAD_CONTROL_TASK_EDITED)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#D14776', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#FF7555', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_REMINDER) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_REMINDER)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#27AFFF', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::STATUS_NEW) {
                $count = $contact_history->where('new_status', ContactDataRecordHistory::STATUS_NEW)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#FFC000', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::STATUS_APPOINMENT_NOT_TAKE_PLACE) {
                $count = $contact_history->where('new_status',ContactDataRecordHistory::STATUS_APPOINMENT_NOT_TAKE_PLACE,)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#E4263D', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::STATUS_OPEN) {
                $count = $contact_history->where('new_status',ContactDataRecordHistory::STATUS_OPEN,)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#2761D3', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::STATUS_POSITIVE_COMPLETED) {
                $count = $contact_history->where('new_status',ContactDataRecordHistory::STATUS_POSITIVE_COMPLETED,)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#1FBC5E', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::STATUS_NEGATIVE_COMPLETED) {
                $count = $contact_history->where('new_status',ContactDataRecordHistory::STATUS_NEGATIVE_COMPLETED,)->count();
                $performance_count_per_condition[$condition] = ['count' => $count, 'color' => '#636363', "textColor" => 'white'];
            }
        }

        //Update labels and index based on count;
        $performance_count_per_condition = $performance_count_per_condition->filter(fn ($item) => $item['count'] > 0);

        return [
            'performance_condition_label' => $performance_count_per_condition->keys()->toArray(),
            'performance_count_per_condition' => $performance_count_per_condition->pluck('count')->toArray(),
            'performance_condition_label_color' => $performance_count_per_condition->pluck('color')->toArray(),
            'performance_condition_text_color' => $performance_count_per_condition->pluck('textColor')->toArray()
        ];
    }
    /**
     * Cost Count Pie Chart Data And Labels
     *
     * @return Array
     */
    private function getCostCountPieCartData(Request $request, $contact_data_ids, $cost_condition_label)
    {
        $workflow_setting = WorkflowSetting::latest('created_at')->first();

        $cost_count_per_condition = collect([]);

        foreach ($cost_condition_label as $condition) {
            $contact_history = ContactDataRecordHistory::query()
                ->whereIn('contact_data_record_id', $contact_data_ids)->when(request('start_date') && request('end_date'), function ($q) use ($request) {
                    $start = Carbon::parse(request('start_date'))->startOfDay();
                    $end = Carbon::parse(request('end_date'))->endOfDay();
                    return $q->whereBetween('created_at', [$start, $end]);
                });;

            if ($condition == ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION) {
                $count = $contact_history->where('action', ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION)->count();
                $cost_count_per_condition[$condition] = ['count' =>  $workflow_setting->contact_record_creation_cost ? $workflow_setting->contact_record_creation_cost * $count : 0, 'color' => '#BA27FF', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::LEAD_QUALITY_CHECK) {
                $count = $contact_history->where('action', ContactDataRecordHistory::LEAD_QUALITY_CHECK)->count();
                $cost_count_per_condition[$condition] = ['count' => $workflow_setting->lead_quality_check_cost ? $workflow_setting->lead_quality_check_cost * $count : 0, 'color' => '#5B7DFF', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL)->count();
                $cost_count_per_condition[$condition] = ['count' => $workflow_setting->appointment_contact_cost ? $workflow_setting->appointment_contact_cost * $count : 0, 'color' => '#323232', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_CONTROL_TASK_EDITED) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_CONTROL_TASK_EDITED)->count();
                $cost_count_per_condition[$condition] = ['count' => $workflow_setting->edit_appointment_quality_topics_cost ? $workflow_setting->edit_appointment_quality_topics_cost * $count : 0, 'color' => '#FFC000', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::DATA_VERIFICATION_UPDATE) {
                $count = $contact_history->where('action', ContactDataRecordHistory::DATA_VERIFICATION_UPDATE)->count();
                $cost_count_per_condition[$condition] = ['count' => $workflow_setting->data_verification_cost ? $workflow_setting->data_verification_cost * $count : 0, 'color' => '#26CD4A', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::LEAD_CONTROL_TASK_EDITED) {
                $count = $contact_history->where('action', ContactDataRecordHistory::LEAD_CONTROL_TASK_EDITED)->count();
                $cost_count_per_condition[$condition] = ['count' => $workflow_setting->edit_lead_quality_topics_cost ? $workflow_setting->edit_lead_quality_topics_cost * $count : 0, 'color' => '#D14776', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK)->count();
                $cost_count_per_condition[$condition] = ['count' => $workflow_setting->appointment_quality_check_cost ? $workflow_setting->appointment_quality_check_cost * $count : 0, 'color' => '#FF7555', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_REMINDER) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_REMINDER)->count();
                $cost_count_per_condition[$condition] = ['count' => $workflow_setting->carry_out_appointment_reminder_cost ? $workflow_setting->carry_out_appointment_reminder_cost * $count : 0, 'color' => '#27AFFF', "textColor" => 'white'];
            }
        }

        //Update labels and index based on count;
        $cost_count_per_condition = $cost_count_per_condition->filter(fn ($item) => $item['count'] > 0);

        return [
            'cost_condition_label' => $cost_count_per_condition->keys()->toArray(),
            'cost_count_per_condition' => $cost_count_per_condition->pluck('count')->toArray(),
            'cost_condition_label_color' => $cost_count_per_condition->pluck('color')->toArray(),
            'cost_condition_text_color' => $cost_count_per_condition->pluck('textColor')->toArray()
        ];
    }
    /**
     * Success Count Pie Chart Data And Labels
     *
     * @return Array
     */
    private function getSuccessCountPieCartData(Request $request, $contact_data_ids, $success_condition_label)
    {

        $success_count_per_condition = collect([]);

        foreach ($success_condition_label as $condition) {
            $contact_history = ContactDataRecordHistory::query()
                ->whereIn('contact_data_record_id', $contact_data_ids)->when(request('start_date') && request('end_date'), function ($q) use ($request) {
                    $start = Carbon::parse(request('start_date'))->startOfDay();
                    $end = Carbon::parse(request('end_date'))->endOfDay();
                    return $q->whereBetween('created_at', [$start, $end]);
                });;

            if ($condition == ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION) {
                $count = $contact_history->where('action', ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION)->where('new_status', 'Positive completed')->count();
                $success_count_per_condition[$condition] = ['count' =>  $count, 'color' => '#BA27FF', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::LEAD_QUALITY_CHECK) {
                $count = $contact_history->where('action', ContactDataRecordHistory::LEAD_QUALITY_CHECK)->where('new_status', 'Positive completed')->count();
                $success_count_per_condition[$condition] = ['count' => $count, 'color' => '#5B7DFF', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL)->where('new_status', 'Positive completed')->count();
                $success_count_per_condition[$condition] = ['count' => $count, 'color' => '#323232', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_CONTROL_TASK_EDITED) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_CONTROL_TASK_EDITED)->where('new_status', 'Positive completed')->count();
                $success_count_per_condition[$condition] = ['count' => $count, 'color' => '#FFC000', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::DATA_VERIFICATION_UPDATE) {
                $count = $contact_history->where('action', ContactDataRecordHistory::DATA_VERIFICATION_UPDATE)->where('new_status', 'Positive completed')->count();
                $success_count_per_condition[$condition] = ['count' => $count, 'color' => '#26CD4A', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::LEAD_CONTROL_TASK_EDITED) {
                $count = $contact_history->where('action', ContactDataRecordHistory::LEAD_CONTROL_TASK_EDITED)->where('new_status', 'Positive completed')->count();
                $success_count_per_condition[$condition] = ['count' => $count, 'color' => '#D14776', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK)->where('new_status', 'Positive completed')->count();
                $success_count_per_condition[$condition] = ['count' => $count, 'color' => '#FF7555', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_REMINDER) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_REMINDER)->where('new_status', 'Positive completed')->count();
                $success_count_per_condition[$condition] = ['count' => $count, 'color' => '#27AFFF', "textColor" => 'white'];
            }
        }

        //Update labels and index based on count;
        $success_count_per_condition = $success_count_per_condition->filter(fn ($item) => $item['count'] > 0);

        return [
            'success_condition_label' => $success_count_per_condition->keys()->toArray(),
            'success_count_per_condition' => $success_count_per_condition->pluck('count')->toArray(),
            'success_condition_label_color' => $success_count_per_condition->pluck('color')->toArray(),
            'success_condition_text_color' => $success_count_per_condition->pluck('textColor')->toArray()
        ];
    }
    /**
     * Profit Count Pie Chart Data And Labels
     *
     * @return Array
     */
    private function getProfitCountPieCartData(Request $request, $contact_data_ids, $profit_condition_label)
    {

        $workflow_setting = WorkflowSetting::latest('created_at')->first();
        $profit_count_per_condition = collect([]);

        foreach ($profit_condition_label as $condition) {
            $contact_history = ContactDataRecordHistory::query()
                ->whereIn('contact_data_record_id', $contact_data_ids)->when(request('start_date') && request('end_date'), function ($q) use ($request) {
                    $start = Carbon::parse(request('start_date'))->startOfDay();
                    $end = Carbon::parse(request('end_date'))->endOfDay();
                    return $q->whereBetween('created_at', [$start, $end]);
                });;

            if ($condition == ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION) {
                $count = $contact_history->where('action', ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION)->where('new_status', 'Positive completed')->count();
                $total_cost = $workflow_setting->contact_record_creation_cost ? $workflow_setting->contact_record_creation_cost * $count : 0;
                $total_revenue = $workflow_setting->contact_record_creation_revenue ? $workflow_setting->contact_record_creation_revenue * $count : 0;
                $total_profit = $total_revenue - $total_cost;
                $profit_count_per_condition[$condition] = ['count' =>  $total_profit ?? 0, 'color' => '#BA27FF', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::LEAD_QUALITY_CHECK) {
                $count = $contact_history->where('action', ContactDataRecordHistory::LEAD_QUALITY_CHECK)->where('new_status', 'Positive completed')->count();
                $total_cost = $workflow_setting->lead_quality_check_cost ? $workflow_setting->lead_quality_check_cost * $count : 0;
                $total_revenue = $workflow_setting->lead_quality_check_revenue ? $workflow_setting->lead_quality_check_revenue * $count : 0;
                $total_profit = $total_revenue - $total_cost;
                $profit_count_per_condition[$condition] = ['count' => $total_profit ?? 0, 'color' => '#5B7DFF', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL)->where('new_status', 'Positive completed')->count();
                $total_cost = $workflow_setting->appointment_contact_cost ? $workflow_setting->appointment_contact_cost * $count : 0;
                $total_revenue = $workflow_setting->appointment_contact_revenue ? $workflow_setting->appointment_contact_revenue * $count : 0;
                $total_profit = $total_revenue - $total_cost;
                $profit_count_per_condition[$condition] = ['count' => $total_profit, 'color' => '#323232', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_CONTROL_TASK_EDITED) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_CONTROL_TASK_EDITED)->where('new_status', 'Positive completed')->count();
                $total_cost = $workflow_setting->edit_appointment_quality_topics_cost ? $workflow_setting->edit_appointment_quality_topics_cost * $count : 0;
                $total_revenue = $workflow_setting->edit_appointment_quality_topics_revenue ? $workflow_setting->edit_appointment_quality_topics_revenue * $count : 0;
                $total_profit = $total_revenue - $total_cost;
                $profit_count_per_condition[$condition] = ['count' => $total_profit, 'color' => '#FFC000', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::DATA_VERIFICATION_UPDATE) {
                $count = $contact_history->where('action', ContactDataRecordHistory::DATA_VERIFICATION_UPDATE)->where('new_status', 'Positive completed')->count();
                $total_cost = $workflow_setting->data_verification_cost ? $workflow_setting->data_verification_cost * $count : 0;
                $total_revenue = $workflow_setting->data_verification_revenue ? $workflow_setting->data_verification_revenue * $count : 0;
                $total_profit = $total_revenue - $total_cost;
                $profit_count_per_condition[$condition] = ['count' => $total_profit, 'color' => '#26CD4A', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::LEAD_CONTROL_TASK_EDITED) {
                $count = $contact_history->where('action', ContactDataRecordHistory::LEAD_CONTROL_TASK_EDITED)->where('new_status', 'Positive completed')->count();
                $total_cost = $workflow_setting->edit_lead_quality_topics_cost ? $workflow_setting->edit_lead_quality_topics_cost * $count : 0;
                $total_revenue = $workflow_setting->edit_lead_quality_topics_revenue ? $workflow_setting->edit_lead_quality_topics_revenue * $count : 0;
                $total_profit = $total_revenue - $total_cost;
                $profit_count_per_condition[$condition] = ['count' => $total_profit, 'color' => '#D14776', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK)->where('new_status', 'Positive completed')->count();
                $total_cost = $workflow_setting->appointment_quality_check_cost ? $workflow_setting->appointment_quality_check_cost * $count : 0;
                $total_revenue = $workflow_setting->appointment_quality_check_revenue ? $workflow_setting->appointment_quality_check_revenue * $count : 0;
                $total_profit = $total_revenue - $total_cost;
                $profit_count_per_condition[$condition] = ['count' => $total_profit, 'color' => '#FF7555', "textColor" => 'white'];
            }
            if ($condition == ContactDataRecordHistory::APPOINTMENT_REMINDER) {
                $count = $contact_history->where('action', ContactDataRecordHistory::APPOINTMENT_REMINDER)->where('new_status', 'Positive completed')->count();
                $total_cost = $workflow_setting->carry_out_appointment_reminder_cost ? $workflow_setting->carry_out_appointment_reminder_cost * $count : 0;
                $total_revenue = $workflow_setting->carry_out_appointment_reminder_revenue ? $workflow_setting->carry_out_appointment_reminder_revenue * $count : 0;
                $total_profit = $total_revenue - $total_cost;
                $profit_count_per_condition[$condition] = ['count' => $count, 'color' => '#27AFFF', "textColor" => 'white'];
            }
        }

        //Update labels and index based on count;
        $profit_count_per_condition = $profit_count_per_condition->filter(fn ($item) => $item['count'] > 0);

        return [
            'profit_condition_label' => $profit_count_per_condition->keys()->toArray(),
            'profit_count_per_condition' => $profit_count_per_condition->pluck('count')->toArray(),
            'profit_condition_label_color' => $profit_count_per_condition->pluck('color')->toArray(),
            'profitcondition_text_color' => $profit_count_per_condition->pluck('textColor')->toArray()
        ];
    }
}
