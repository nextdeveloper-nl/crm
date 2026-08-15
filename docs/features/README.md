# CRM Platform Features

This is the feature documentation for the platform's Customer Relationship Management (CRM) capabilities — accounts, contacts, the sales pipeline, campaigns, and account health. Each page below explains a feature area in plain language and includes brief API examples for the key operations in that area.

All API paths are relative to the platform's API base URL and prefixed with `/crm`. Resources are addressed by UUID and follow the same basic operations: list (`GET`), create (`POST`), show/update/delete (`GET` / `PATCH` / `DELETE` on `/{id}`), and custom actions (`POST /{id}/do/{action}`).

## Feature Areas

| Feature | What it covers |
| --- | --- |
| [Accounts & Contacts](accounts-and-contacts.md) | CRM accounts, contacts, account managers, and classification (industry, sector, technology) |
| [Sales Pipeline](sales-pipeline.md) | Opportunities, quotes, quote items, offerings, and projects |
| [Campaigns & Targeting](campaigns-and-targeting.md) | Target lists, campaigns, campaign targets, and email templates |
| [Activities & Tasks](activities-and-tasks.md) | Calls, meetings, notes, and tasks logged against an account |
| [Account Health & Risk](account-health-and-risk.md) | Risk scoring, suspension, disabling, and per-service enable/disable |
| [Performance & Reporting](performance-and-reporting.md) | Account manager, pipeline, and account-growth performance over time |

## How These Pages Are Organized

Each page follows the same structure: a short overview, a list of key capabilities, a deeper explanation of each sub-feature, brief API examples for the most common operations, and links to related feature areas.
