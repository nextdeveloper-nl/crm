# Campaigns & Targeting

Outreach is organized around campaigns aimed at a defined list of contacts. A target is a reusable list of people, a campaign is a time-boxed effort that uses one or more target lists, and email templates supply the content sent during that effort.

## Key Capabilities

- Build named target lists of contacts for outreach
- Run campaigns with a start and end date, a type, and a status
- Attach one or more target lists to a campaign
- Tie a campaign into a workflow pipeline/stage for automated follow-up
- Create reusable email templates tied to a campaign and a communication channel

## Targets and Target Users

A target is a named, reusable list — for example, "Trial users who haven't upgraded" — with a type describing what kind of list it is and a count of how many contacts it currently holds. Target users link individual CRM contacts into that list, so the same list can be reused across multiple campaigns without rebuilding it each time.

## Campaigns

A campaign is a time-boxed outreach effort with a start date, end date, status, and type. Campaigns can be linked into the platform's workflow automation (a pipeline and stage), which lets a campaign trigger or respond to automated steps beyond simply sending emails. A campaign can also be the origin of a sales opportunity, connecting marketing effort directly to pipeline results.

## Campaign Targets

A campaign can be aimed at one or more target lists by linking them together — this is what determines who actually receives a given campaign's outreach.

## Email Templates

An email template stores the subject and content used for a campaign's outreach on a particular communication channel, so the same message can be reused or referenced consistently across a campaign's lifetime.

## API Examples

**Create a target list**

```
POST /crm/targets
```
```json
{
  "name": "Trial users - 30 days",
  "type": "lead-list"
}
```

**Add a contact to a target list**

```
POST /crm/target-users
```
```json
{
  "crm_target_id": "2c5d...-uuid",
  "crm_user_id": "8f1e...-uuid"
}
```

**Create a campaign**

```
POST /crm/campaigns
```
```json
{
  "name": "Q3 Upgrade Push",
  "start_date": "2026-07-01",
  "end_date": "2026-09-30",
  "campaign_type": "email",
  "status": "active"
}
```

**Attach a target list to a campaign**

```
POST /crm/campaign-targets
```
```json
{
  "crm_target_id": "2c5d...-uuid",
  "crm_campaign_id": "6b9a...-uuid"
}
```

**Create an email template for a campaign**

```
POST /crm/email-templates
```
```json
{
  "subject": "Ready to upgrade?",
  "content": "<p>Hi {{name}}, ...</p>",
  "crm_campaign_id": "6b9a...-uuid"
}
```

## Related Features

- [Accounts & Contacts](accounts-and-contacts.md) — campaign targets are drawn from CRM contacts
- [Sales Pipeline](sales-pipeline.md) — campaigns can generate opportunities
