# Accounts & Contacts

The CRM layer keeps a sales-and-relationship view of every customer, separate from their billing/identity account. A CRM account tracks how healthy and valuable a customer relationship is, while CRM contacts track the actual people you talk to — their role, preferences, and even personal details that help build the relationship.

## Key Capabilities

- A CRM account wraps a platform account with sales-relevant data: paying-customer status, risk level, and service status
- A CRM contact wraps a platform user with relationship details: job title, interests, and rapport-building notes
- Assign one or more account managers (sales reps) to look after a given account
- Classify accounts by industry, sector focus, technology stack, and regulatory requirements
- Track a "watch" list of accounts an account manager is actively following

## CRM Accounts

A CRM account is linked one-to-one with a platform account and adds a sales perspective on top of it: whether the account is currently a paying customer, its risk level, whether it's suspended or disabled (and why), and whether it has been qualified by a sales development rep (SDR). This is the record sales and customer success teams work from, separate from the billing/account-settings view.

## CRM Contacts (Users)

A CRM contact extends a platform user with relationship-building details — job title and description, hobbies, relationship status, education level, and a personal risk indicator — useful for account managers who want to build rapport, not just track a ticket queue. A contact can also be marked as an "evangelist," flagging them as a strong advocate for your product within their organization.

## Account Managers

An account manager is a platform user assigned to look after a specific CRM account. An account can have more than one manager, and a manager can watch an account to start following its activity without necessarily owning it outright.

## Classification

Accounts can be tagged with industries, sector focus areas, technologies they use, and regulatory compliance requirements they're subject to. These are simple, reusable lookup lists that help with segmentation, targeted campaigns, and reporting.

## API Examples

**Create a CRM account for an existing platform account**

```
POST /crm/accounts
```
```json
{
  "iam_account_id": "9d4e...-uuid",
  "is_paying_customer": false
}
```

**Assign an account manager**

```
POST /crm/account-managers
```
```json
{
  "crm_account_id": "4a1f...-uuid",
  "iam_user_id": "3f9c1a20-...-uuid"
}
```

**Watch an account**

```
POST /crm/accounts/{id}/do/watch-account
```

**Create a CRM contact for a platform user**

```
POST /crm/users
```
```json
{
  "iam_user_id": "3f9c1a20-...-uuid",
  "position": "VP of Engineering",
  "is_evangelist": true
}
```

## Related Features

- [Account Health & Risk](account-health-and-risk.md) — suspension, risk scoring, and service status for an account
- [Sales Pipeline](sales-pipeline.md) — opportunities and quotes are tied to a CRM account
- [Activities & Tasks](activities-and-tasks.md) — calls, meetings, and notes logged against an account
