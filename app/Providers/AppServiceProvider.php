<?php

namespace App\Providers;

use App\Events\TicketCreated;
use App\Events\TicketUpdated;
use App\Listeners\SendNewTicketMail;
use App\Listeners\SendTicketUpdateMail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Force Laravel to use Bootstrap for pagination links
        Paginator::useBootstrap();

        // 2. Manually register the events and their listeners
        // This ensures emails are sent when a ticket is created
        Event::listen(
            TicketCreated::class,
            SendNewTicketMail::class,
        );

        // This ensures emails are sent when a reply is added or ticket updated
        Event::listen(
            TicketUpdated::class,
            SendTicketUpdateMail::class,
        );
    }
}