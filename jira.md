# App Pricing & Plans Management

## Objective

- Create an enterprise structure for this app as a SaaS app.
- This will gonna use for the entire app or feature as limiting.
- Implement CRUD operations, for create, updating, and deleting the content of Plans or Pricing.
- Support localization of currency, like Pesos, Dollar, Yen, etc...
- Give a decent plan name and description.
- The plans limiting are following:
    1. Free Plan:
        - Journal Entries, Unlimited
        - Notes/Second Brain, Unlimited
        - Attachments, 500MB
        - Reminders, 10 Active
        - Pomodoro, Unlimited
        - Kanban boards, 3 Boards
        - Kanban tasks, 100 tasks
        - Publishing Platform, Not included
    2. Pro Plan:
        - Journal Entries, Unlimited
        - Notes/Second Brain, Unlimited
        - Attachments, 5GB
        - Reminders, Unlimited
        - Pomodoro, Unlimited
        - Kanban boards, 10 Boards
        - Kanban tasks, Unlimited
        - Publishing Platform, Included
    3. MindForge Plan:
        - Journal Entries, Unlimited
        - Notes/Second Brain, Unlimited
        - Attachments, 25GB
        - Reminders, Unlimited
        - Pomodoro, Unlimited
        - Kanban boards, Unlimited
        - Kanban tasks, Unlimited
        - Publishing Platform, Included

## Code Pattern

- Thin Controller
- Use Form request or Make Request of laravel
- Use our abstact controller for the success and error method.
- Service layer as business logic
- new migration and with support UUID. foreign id for Table Relational or referencing, UUID is for business action like create, update, delete.

## Files

- Controller - `/home/projects/actamind-app/actamind-be/app/Http/Controllers/PlansController.php`
- Service - `/home/projects/actamind-app/actamind-be/app/Services/Plans`
- Enum - `/home/projects/actamind-app/actamind-be/app/Enum`
- Route - `/home/projects/actamind-app/actamind-be/routes/v1/plans.php`
- Migration - `/home/projects/actamind-app/actamind-be/routes/v1/plans.php`
- Model - `/home/projects/actamind-app/actamind-be/app/Models/Plan.php`

## Seeder

Create an seeder for Plans
