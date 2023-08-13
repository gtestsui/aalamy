<?php

namespace App\Jobs;

use App\Models\NewsLetterRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewsLetterMailSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $newsLetter;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($newsLetter)
    {
        $this->newsLetter = $newsLetter;
        $this->handle();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->newsLetter;
        $array_emails = NewsLetterRequest::pluck('email')->toArray();
        foreach ($array_emails as $email){
            Mail::send('mail-NewsLetter', ['data'=> $data], function($message) use($email){
                $message->to($email)
                    ->subject('ArabTeco NewsLetter');
                $message->from('alisrourali3333@gmail.com','ArabTeco');
            });
        }
    }
}
