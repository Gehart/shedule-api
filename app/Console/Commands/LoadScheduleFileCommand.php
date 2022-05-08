<?php

namespace App\Console\Commands;

use App\Exceptions\ExceptionWithContextInterface;
use App\Service\ScheduleFileProcessingService;
use Illuminate\Console\Command;

class LoadScheduleFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zig:load {file} {dateStart?}';

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
        $dateStart = $this->argument('dateStart');

        try {
            $this->scheduleFileProcessingService->getScheduleFromFile($filepath, $dateStart);
        } catch (\Throwable $e) {
            $this->error('Error');
            $this->warn($e);
            if ($e instanceof ExceptionWithContextInterface) {
                $this->warn('Context' . json_encode([
                        'context' => $e->getContext(),
                    ]) );
            }
        }
        $this->info('Command finished!');
    }
}
