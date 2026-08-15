# Activities & Tasks

Every interaction with a customer — a call, a meeting, a quick note — gets logged against their account, building a history that anyone on the team can pick up. Tasks layer on top as reminders and to-dos tied to that same account or opportunity.

## Key Capabilities

- Log calls with direction, duration, and outcome (disposition)
- Log meetings with notes, outcomes, and customer requirements gathered
- Attach freeform notes to an account at any time
- Create tasks with a due date and priority, attachable to any CRM record
- Track whether a task is finished or running late

## Calls

A call log records who was contacted, the direction of the call (inbound or outbound), the phone numbers involved, how long it lasted, and its disposition (the outcome — for example, connected, no answer, or voicemail). A call can be linked to both the account and, if relevant, a specific opportunity it relates to.

## Meetings

A meeting log captures more context than a call: the meeting's outcome, notes taken during the meeting, customer requirements that came up, and any suggestions made. Meetings can link to a calendar item, account, and opportunity, keeping everything connected to the same deal.

## Notes

A note is the simplest activity type — a freeform piece of text attached to an account, useful for anything that doesn't need the structure of a call or meeting log.

## Tasks

A task is a to-do with a name, description, priority, and due date. Tasks attach to any CRM object generically (an account, an opportunity, etc.) via a polymorphic reference, and track both whether they're finished and whether they're currently overdue.

## API Examples

**Log a call**

```
POST /crm/calls
```
```json
{
  "name": "Check-in call",
  "description": "Quarterly check-in with the customer",
  "crm_account_id": "4a1f...-uuid",
  "call_direction": "outbound",
  "from_number": "+15551234567",
  "to_number": "+15559876543",
  "duration": 320,
  "disposition": "connected"
}
```

**Log a meeting**

```
POST /crm/meetings
```
```json
{
  "crm_account_id": "4a1f...-uuid",
  "crm_opportunity_id": "7e2a...-uuid",
  "meeting_note": "Discussed upgrade timeline",
  "outcome": "positive"
}
```

**Add a note to an account**

```
POST /crm/notes
```
```json
{
  "crm_account_id": "4a1f...-uuid",
  "note": "Customer asked about annual billing discount"
}
```

**Create a task**

```
POST /crm/tasks
```
```json
{
  "name": "Follow up on quote",
  "description": "Check in after sending the quote",
  "crm_account_id": "4a1f...-uuid",
  "object_type": "NextDeveloper\\CRM\\Database\\Models\\Opportunities",
  "object_id": 42,
  "priority": 1,
  "due_date": "2026-07-05"
}
```

## Related Features

- [Accounts & Contacts](accounts-and-contacts.md) — activities are logged against a CRM account
- [Sales Pipeline](sales-pipeline.md) — calls, meetings, and tasks often relate to a specific opportunity
