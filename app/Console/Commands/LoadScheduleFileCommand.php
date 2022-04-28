<?php

namespace App\Console\Commands;

use App\Service\ScheduleFileProcessingService;
use Illuminate\Console\Command;

class LoadScheduleFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zig:load {file}';

    /**
     * @var string
     */
    protected $description = 'Load a schedule file
    {file: Файл с расписанием}';

    public function __construct(
        private ScheduleFileProcessingService $scheduleFileProcessingService,
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Start command');
        $filepath = ROOT_DIR . '/' . ltrim($this->argument('file'), '/');

        try {
            $this->scheduleFileProcessingService->getScheduleFromFile($filepath);
        } catch (\Throwable $e) {
            $this->error('Error');
            $this->warn($e->getMessage());
        }
        $this->info('Command finished!');
    }
}
