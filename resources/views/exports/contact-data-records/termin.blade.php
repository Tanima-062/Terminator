@php
    $lang_code = auth()->user()->language->code;
    // dd($records)
@endphp
<table>
    <thead>
    <tr>
        <th>{{ __('Appointment ID', [], $lang_code) }}</th>
        <th>{{ __('Appointment Date', [], $lang_code) }}</th>
        <th>{{ __('Appointment Time', [], $lang_code) }}</th>
        <th>{{ __('Allocated to', [], $lang_code) }}</th>
        <th>{{ __('VariableA', [], $lang_code) }}</th>
        <th>{{ __('Campaign', [], $lang_code) }}</th>
        <th>{{ __('Correspondence Language', [], $lang_code) }}</th>
        <th>{{ __('Canton', [], $lang_code) }}</th>
        <th>{{ __('First Name', [], $lang_code) }}</th>
        <th>{{ __('Last Name', [], $lang_code) }}</th>
        <th>{{ __('Zip Code PLZ', [], $lang_code) }}</th>
        <th>{{ __('City', [], $lang_code) }}</th>
        <th>{{ __('Number of persons in household', [], $lang_code) }}</th>
        <th>{{ __('Control status (Appointment)', [], $lang_code) }}</th>
        <th>{{ __('Notes Control (Appointment)', [], $lang_code) }}</th>
        <th>{{ __('Contact record status', [], $lang_code) }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
        <tr>
             <td>{{ $record->lastAppointment?->prefix_id }}</td>
             <td>
                {{ $record->lastAppointment? Illuminate\Support\Carbon::parse($record->lastAppointment->appointment_date)->format('d.m.Y') : '' }}</td>
            <td>
                {{ $record->lastAppointment? Illuminate\Support\Carbon::createFromFormat('H:i:s', $record->lastAppointment->appointment_time)->format('H:i') : '' }}
            </td>
             <td>
                @if($record->allocation)
                    @if ($record->allocation->type == 'Broker user')
                        {{ $record->allocation->user->full_name }}
                    @elseif($record->allocation->type == 'Leader Head of' || $record->allocation->type == 'Manager' || $record->allocation->type == 'Quality controller' || $record->allocation->type == 'Call agent')
                        {{ $record->allocation->user->full_name }}
                    @elseif($record->allocation->type == 'Broker')
                        {{ $record->allocation->broker?->name }}
                    @endif
                @endif
             </td>
             <td>
                @if($record->allocation)
                    @if ($record->allocation->type == 'variableA')
                        {{ $record->allocation->organizationElement->name }}
                    @endif
                @endif
             </td>
             <td>{{ $record->campaign->name  }}</td>
             <td>{{ $languages->{$record->correspondence_language}  }}</td>
             <td>{{ $record->canton  }}</td>
             <td>{{ $record->first_name  }}</td>
             <td>{{ $record->last_name  }}</td>
             <td>{{ $record->zip_code  }}</td>
             <td>{{ $record->city  }}</td>
             <td>{{ $record->number_of_persons_in_household  }}</td>
             <td>{{ $record->lastAppointment? $record->lastAppointment->control_status_appointment : '' }}</td>
             <td>{{ $record->lastAppointment? $record->lastAppointment->notes : '' }}</td>
             <td>{{ trans(ucfirst($record->contact_record_status), [], $lang_code)  }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
