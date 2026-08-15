# Performance & Reporting

The CRM tracks sales and account-management performance over time, rather than only showing current state — making it possible to see trends in new accounts, paying customers, and pipeline movement, broken down by salesperson.

## Key Capabilities

- Track account manager performance over time
- Track new accounts acquired, broken down weekly, monthly, and by distribution channel
- Track how many accounts are paying customers each month
- Track opportunity (pipeline) performance over time
- A consolidated view of salespeople and the accounts/opportunities they own

## Account Manager Performance

This tracks how each account manager is performing — useful for sales leadership reviewing team performance, coaching, or compensation, without needing to manually cross-reference accounts and opportunities per rep.

## New Account and Paying Customer Trends

Several views track account growth over time: new accounts per week, new accounts per month, new accounts per month broken down by distribution channel, and how many accounts are paying customers in a given month. These are read-only reporting views meant for dashboards rather than resources you create directly.

## Opportunity Performance

This tracks how opportunities are progressing and closing over time — a pipeline-level view rather than a single-deal view, useful for forecasting and spotting whether deals are slowing down at a particular stage.

## Sales People Overview

A consolidated, read-only view of each salesperson alongside the accounts and opportunities tied to them, useful as a quick "who owns what" reference without joining several resources together manually.

## API Examples

**View account manager performance**

```
GET /crm/account-managers-performance
```

**View monthly new account trends**

```
GET /crm/monthly-new-accounts-performance
```

**View weekly new account trends**

```
GET /crm/weekly-new-accounts-performance
```

**View opportunity pipeline performance**

```
GET /crm/opportunities-performance
```

**View the salespeople overview**

```
GET /crm/sales-people-perspective
```

## Related Features

- [Sales Pipeline](sales-pipeline.md) — the underlying opportunities and quotes these reports summarize
- [Account Health & Risk](account-health-and-risk.md) — paying-customer status feeds into these trends
