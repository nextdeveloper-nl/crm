# Account Health & Risk

Beyond tracking the relationship, the CRM keeps a running read on how healthy and low-risk an account is — informed by who the contacts are, how reliably they pay, and how they actually use the service — and gives account managers direct controls to suspend, disable, or re-enable an account's service when something needs attention.

## Key Capabilities

- Calculate a risk score for an account based on user information, payment cycle history, and service usage behavior
- Suspend or unsuspend an account
- Disable or re-enable an account outright, with a recorded reason
- Enable or disable specific services for an account independently of suspension
- Mark an account as SDR-qualified (vetted by a sales development rep) or flag it as needing qualification
- Watch an account to start following its activity as an account manager

## Risk Scoring

An account's risk level is calculated by looking at three areas: information about the people associated with the account, their payment cycle history, and how they actually use the service day to day. The result is stored as a numeric risk level on the account, which can be recalculated at any time as new activity comes in.

## Suspension and Disabling

These are two distinct, independent states:

- **Suspended** — typically a softer, reversible state (with a recorded suspension reason) that can be lifted by unsuspending the account
- **Disabled** — a more deliberate state (with a recorded disabling reason) for accounts that shouldn't be active

Suspending or disabling a CRM account also affects the underlying platform account it's linked to, so the effect isn't just cosmetic within the CRM.

## Per-Service Enable/Disable

Independently of the account's overall suspension state, individual services tied to the account can be enabled or disabled — useful when only part of an account's access needs to change rather than the whole relationship.

## SDR Qualification

New accounts can be flagged as requiring qualification from a sales development rep before being treated as a real opportunity, and once reviewed, marked as qualified (or disqualified, with a reason) accordingly.

## API Examples

**Recalculate an account's risk level**

```
POST /crm/accounts/{id}/do/calculate-account-risk
```

**Suspend an account**

```
POST /crm/accounts/{id}/do/suspend-account
```

**Unsuspend an account**

```
POST /crm/accounts/{id}/do/unsuspend-account
```

**Disable an account**

```
POST /crm/accounts/{id}/do/disable-account
```

**Enable or disable a specific service**

```
POST /crm/accounts/{id}/do/enable-service
POST /crm/accounts/{id}/do/disable-service
```

## Related Features

- [Accounts & Contacts](accounts-and-contacts.md) — risk and suspension state lives on the CRM account
- [Performance & Reporting](performance-and-reporting.md) — paying-customer and account health trends over time
