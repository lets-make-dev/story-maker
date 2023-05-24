<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\ChapterPart;
use App\Observers\BookObserver;
use App\Observers\ChapterOberserver;
use App\Observers\ChapterPartOberserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        Chapter::class => [ChapterOberserver::class],
        ChapterPart::class => [ChapterPartOberserver::class],

        Book::class => [BookObserver::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
