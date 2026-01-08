<?php

namespace Webkul\Appointment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Appointment Created
        \Webkul\Appointment\Events\AppointmentCreated::class => [
            \Webkul\Appointment\Listeners\SendAppointmentCreatedNotification::class,
        ],

        // Appointment Updated
        \Webkul\Appointment\Events\AppointmentUpdated::class => [
            \Webkul\Appointment\Listeners\SendAppointmentUpdatedNotification::class,
        ],

        // Appointment Confirmed
        \Webkul\Appointment\Events\AppointmentConfirmed::class => [
            \Webkul\Appointment\Listeners\SendAppointmentConfirmedNotification::class,
        ],

        // Appointment Cancelled
        \Webkul\Appointment\Events\AppointmentCancelled::class => [
            \Webkul\Appointment\Listeners\SendAppointmentCancelledNotification::class,
        ],

        // Appointment Rescheduled
        \Webkul\Appointment\Events\AppointmentRescheduled::class => [
            \Webkul\Appointment\Listeners\SendAppointmentRescheduledNotification::class,
        ],

        // Appointment Reminder
        \Webkul\Appointment\Events\AppointmentReminder::class => [
            \Webkul\Appointment\Listeners\SendAppointmentReminderNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
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
