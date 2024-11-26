<?php

namespace App\Console\Commands;

use App\Mail\AppMail;
use App\Models\Pendingmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Sendmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Pendingmail::where('retry', '>', 20)->delete();
        foreach (Pendingmail::lockForUpdate()->get() as $mail) {
            try {
                Mail::to($mail->to)->send(new AppMail((object)['subject' => $mail->subject, 'msg' => $mail->text]));
                $mail->delete();
            } catch (\Throwable $th) {
                $mail->increment('retry');
                //throw $th;
            }
        }
        return 0;
    }
}
