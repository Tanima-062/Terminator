@php
    $lang_code = auth()->user()->language->code
@endphp
<table>
    <thead>
    <tr>
        <th>{{ __('ID', [], $lang_code) }}</th>
        <th>{{ __('Creation Date', [], $lang_code) }}</th>
        <th>{{ __('Creator', [], $lang_code) }}</th>
        <th>{{ __('Allocated to', [], $lang_code) }}</th>
        <th>{{ __('VariableA', [], $lang_code) }}</th>
        <th>{{ __('Campaign', [], $lang_code) }}</th>
        <th>{{ __('Correspondence Language', [], $lang_code) }}</th>
        <th>{{ __('Canton', [], $lang_code) }}</th>
        <th>{{ __('First Name', [], $lang_code) }}</th>
        <th>{{ __('Last Name', [], $lang_code) }}</th>
        <th>{{ __('Year', [], $lang_code) }}</th>
        <th>{{ __('Last Feedback', [], $lang_code) }}</th>
        <th>{{ __('Feedback Time', [], $lang_code) }}</th>
        <th>{{ __('Control status', [], $lang_code) }}</th>
        <th>{{ __('Contact record status', [], $lang_code) }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
        <tr>
             <td>{{ $record->prefix_id }}</td>
             <td>{{ $record->created_at->format('d.m.Y')  }}</td>
             <td>{{ $record->creator->full_name  }}</td>
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
             <td>{{ $record->date_of_birth->format('Y')  }}</td>
             <td>{{ $record->lastFeedback ? $record->lastFeedback->feedback : ''  }}</td>
             <td>{{ $record->lastFeedback ? $record->lastFeedback->created_at->format('d.m.Y') : ''  }}</td>
             <td>{{ $record->lead ? trans(ucfirst($record->lead), [], $lang_code) : '' }}</td>
             <td>{{ trans(ucfirst($record->contact_record_status), [], $lang_code)  }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
