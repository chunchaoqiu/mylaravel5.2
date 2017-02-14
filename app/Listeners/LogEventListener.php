<?php

namespace App\Listeners;

use App\Events\LogEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\LogService;

class LogEventListener
{
    protected $logService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(LogService $logService)
    {
        //
        $this->logService = $logService;
    }

    /**
     * Handle the event.
     *
     * @param  LogEvent  $event
     * @return void
     */
    public function handle(LogEvent $event)
    {
        //

        $this->logService->write($event->getLogContent());

    }
}
