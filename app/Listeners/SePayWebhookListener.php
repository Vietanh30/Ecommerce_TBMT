<?php

namespace App\Listeners;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use SePay\SePay\Notifications\SePayTopUpSuccessNotification;
use App\Events\SePayWebhookEvent;
class SePayWebhookListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SePayWebhookEvent $event): void
    {
        // Xử lý tiền vào tài khoản
        if ($event->sePayWebhookData->transferType === 'in') {
            // Trường hợp $info là user id
            $user = User::query()->where('id', $event->info)->first();
            if ($user instanceof User) {
                $user->notify(new SePayTopUpSuccessNotification($event->sePayWebhookData));
            }
        } else {
            // Xử lý tiền ra tài khoản
        }
    }
}
