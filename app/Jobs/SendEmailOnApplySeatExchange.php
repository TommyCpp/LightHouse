<?php

namespace App\Jobs;

use App\Committee;
use App\Delegation;
use App\Events\SeatExchangeApplied;
use App\Jobs\Job;
use App\SeatExchange;
use Config;
use Event;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SendEmailOnApplySeatExchange
 * 处理席位交换申请时的邮件发送
 * @package App\Jobs
 */
class SendEmailOnApplySeatExchange extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    private $event;

    /**
     * Create a new job instance.
     *
     * @param Event $event
     */
    public function __construct(SeatExchangeAPplied $event)
    {
        //
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     */
    public function handle(Mailer $mailer)
    {

        //当创建名额交换请求时发送邮件
        $seat_exchange = $this->event->seat_exchange_apply;
        $seat_exchange_records = $seat_exchange->seat_exchange_records;
        $records = [];
        $committees = Committee::allInCache();
        foreach ($seat_exchange_records as $seat_exchange_record) {
            $record['committee_name'] = $committees[$seat_exchange_record->committee_id]->chinese_name;
            $record['in'] = $seat_exchange_record->in;
            $record['out'] = $seat_exchange_record->out;
            $records[] = $record;
        }
        $initiator = Delegation::find($seat_exchange->initiator);
        $initiator_head = $initiator->head_delegate;
        $target = Delegation::find($seat_exchange->target);
        $target_head = $target->head_delegate;

        //给发起者发送确认邮件
        $mailer->send("emails.seat-exchange-applied", [
            "is_initiator" => true,
            "delegation_name" => $initiator->name,
            "head_delegate_name" => $initiator_head->name,
            "seat_exchange_id" => $seat_exchange->id,
            "seat_exchange_records" => $records
        ], function (Message $message) use ($initiator_head) {
            $message->from(Config::get("maillist.notify.seat_exchange_applied.sender"));
            $message->to($initiator_head->email)->cc(Config::get("maillist.notify.seat_exchange_applied.ccs"));
            $message->subject(Config::get("maillist.notify.seat_exchange_applied.initiator_subject"));
        });
        //给目标发送确认邮件
        $mailer->send("emails.seat-exchange-applied", [
            "is_initiator" => false,
            "delegation_name" => $target->name,
            "head_delegate_name" => $target_head->name,
            "seat_exchange_id" => $seat_exchange->id,
            "seat_exchange_records" => $records
        ], function (Message $message) use ($target_head) {
            $message->from(Config::get("maillist.notify.seat_exchange_applied.sender"));
            $message->to($target_head->email)->cc(Config::get("maillist.notify.seat_exchange_applied.ccs"));
            $message->subject(Config::get("maillist.notify.seat_exchange_applied.target_subject"));
        });
    }
}
