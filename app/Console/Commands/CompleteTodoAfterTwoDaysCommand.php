<?php

namespace App\Console\Commands;

use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CompleteTodoAfterTwoDaysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:complete-todo-after-two-days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark todos as complete if they are more than two days old.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        dd(Carbon::now()->subDays(2));
        $number_of_updated = Todo::query()
            ->where('is_complete','=',false)
            ->whereDay('created_at','<=' , Carbon::now()->subDays(2))
            ->update(['is_complete' => 1]);
        $this->info("{$number_of_updated} todo(s) marked as complete.");
    }

}
