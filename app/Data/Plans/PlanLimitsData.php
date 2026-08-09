<?php

namespace App\Data\Plans;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class PlanLimitsData extends Data
{
    public function __construct(
        public ?int $journalEntries,
        public ?int $notes,
        public ?int $attachmentsMb,
        public ?int $reminders,
        public ?int $pomodoro,
        public ?int $kanbanBoards,
        public ?int $kanbanTasks,
        public bool $publishingPlatform,
    ) {}
}
