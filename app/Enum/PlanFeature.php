<?php

namespace App\Enum;

enum PlanFeature: string
{
    case JournalEntries = 'journal_entries';
    case Notes = 'notes';
    case AttachmentsMb = 'attachments_mb';
    case Reminders = 'reminders';
    case Pomodoro = 'pomodoro';
    case KanbanBoards = 'kanban_boards';
    case KanbanTasks = 'kanban_tasks';
    case PublishingPlatform = 'publishing_platform';

    /**
     * True for count-style limits (null = unlimited); false for the boolean include/exclude feature.
     */
    public function isNumeric(): bool
    {
        return $this !== self::PublishingPlatform;
    }
}
