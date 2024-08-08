<?php

namespace App\Listeners\Mail;


use App\Events\Mail\BaseEvent;
use App\Events\Mail\BuyerJoinEvent;
use App\Events\Mail\FirstPaidGenerateEvent;
use App\Events\Mail\FreeGenerateCompleteEvent;
use App\Events\Mail\MyPrompt5TimesGeneratedEvent;
use App\Events\Mail\MyPromptFirstGeneratedEvent;
use App\Events\Mail\PointChargeEvent;
use App\Events\Mail\PointLessThanEvent;
use App\Events\Mail\ReachGeneratedRevenueEvent;
use App\Events\Mail\SellerJoinEvent;
use App\Http\Repositories\User\UserRepository;
use App\Service\Mail\MailService;
use App\Service\Mail\Model\Sender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;

class MailEventSubscriber implements ShouldQueue
{
    use InteractsWithQueue;

    public $afterCommit = true;

    public function __construct(
        private UserRepository $userRepository,
        private MailService    $mailService,
    ) {}

    public function handleSellerJoinEvent(SellerJoinEvent $event): void
    {
        $this->sendUserEmail($event);
    }

    public function handleBuyerJoinEvent(BuyerJoinEvent $event): void
    {
        $this->sendUserEmail($event);
    }

    public function handleFreeGenerateCompleteEvent(FreeGenerateCompleteEvent $event): void
    {
        $this->sendUserEmail($event);
    }

    public function handleFirstPaidGenerateEvent(FirstPaidGenerateEvent $event): void
    {
        $this->sendUserEmail($event);
    }

    public function handlePointLessThanEvent(PointLessThanEvent $event): void
    {
        $this->sendUserEmail($event);
    }

    public function handlePointChargeEvent(PointChargeEvent $event): void
    {
        $this->sendUserEmail($event);
    }

    public function handleMyPromptFirstGeneratedEvent(MyPromptFirstGeneratedEvent $event): void
    {
        $this->sendUserEmail($event);
    }

    public function handleMyPrompt5TimesGeneratedEvent(MyPrompt5TimesGeneratedEvent $event): void {
        $this->sendUserEmail($event);
    }

    public function handleReachGeneratedRevenueEvent(ReachGeneratedRevenueEvent $event): void {
        $this->sendUserEmail($event);
    }

    private function getUser(BaseEvent $event): \Illuminate\Database\Eloquent\Model
    {
        return $this->userRepository->showOrFail($event->getUserId());
    }

    private function sendUserEmail(BaseEvent $event): void
    {
        $user = $this->getUser($event);
        $this->mailService->send(
            $this->mailService->getTemplate($event->getEmailId()),
            Sender::first(),
            $user,
            $user,
        );
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            SellerJoinEvent::class => 'handleSellerJoinEvent',
            BuyerJoinEvent::class => 'handleBuyerJoinEvent',
            FreeGenerateCompleteEvent::class => 'handleFreeGenerateCompleteEvent',
            FirstPaidGenerateEvent::class => 'handleFirstPaidGenerateEvent',
            PointLessThanEvent::class => 'handlePointLessThanEvent',
            PointChargeEvent::class => 'handlePointChargeEvent',
            MyPromptFirstGeneratedEvent::class => 'handleMyPromptFirstGeneratedEvent',
            MyPrompt5TimesGeneratedEvent::class => 'handleMyPrompt5TimesGeneratedEvent',
            ReachGeneratedRevenueEvent::class => 'handleReachGeneratedRevenueEvent',
        ];
    }

}
