<?php

namespace App\Providers;

use App\Models\ContactDataRecord;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Models\ContactDataRecordAllocate;
use App\Models\ContactDataRecordFeedback;
use App\Models\ContactDataRecordAppointment;
use App\Observers\ContactDataRecordObserver;
use App\Observers\ContactDataRecordFeedbackObserver;
use App\Observers\ContactDataRecordAllocationObserver;
use App\Observers\ContactDataRecordAppointmentObserver;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $observers = [
        ContactDataRecord::class  => [ContactDataRecordObserver::class,],
        ContactDataRecordAllocate::class  => [ContactDataRecordAllocationObserver::class,],
        ContactDataRecordFeedback::class  => [ContactDataRecordFeedbackObserver::class,],
        ContactDataRecordAppointment::class  => [ContactDataRecordAppointmentObserver::class,],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
