# Sales Pipeline

The sales pipeline tracks a deal from first contact to signed quote — an opportunity captures the potential sale, a quote prices it out, and approving the quote can convert it directly into an invoice.

## Key Capabilities

- Track opportunities through a pipeline stage, with probability, expected income, and a deadline
- Record why an opportunity was lost, for later analysis
- Build quotes against an opportunity, with line items, quantities, and discounts
- Route quotes through a draft → pending-approval → approved workflow
- Convert an approved quote directly into an invoice
- Reassign an opportunity to a different owner
- Track external projects associated with an account or opportunity

## Opportunities

An opportunity represents a potential sale: a name, description, source (how it originated), expected income, a probability of closing, and a deadline. Opportunities move through pipeline stages, and if a deal falls through, the reason it was lost is recorded — useful for spotting patterns over time. An opportunity can also be linked to the campaign that generated it, and ownership can be reassigned to a different salesperson if needed.

## Quotes

A quote is built against an opportunity and lists out pricing: a total amount, a detailed breakdown, and a suggested price. Quotes follow an approval workflow:

1. **Draft** — being put together
2. **Pending approval** — submitted and awaiting sign-off
3. **Approved** — ready to bill

Once a quote is approved, it can be converted into an invoice in one step — the quote is marked as converted and linked to the resulting invoice record, so it can't be converted twice.

## Quote Items

Each quote is made up of line items referencing products from the marketplace catalog, with a quantity, unit price, discount, and calculated total per line.

## Offerings and Projects

Offerings are a simple named catalog of what's being sold, used for grouping or reference. Projects track an external project tied to an account or opportunity — useful when a sale results in a tracked implementation or onboarding project rather than just a subscription.

## API Examples

**Create an opportunity**

```
POST /crm/opportunities
```
```json
{
  "name": "Acme Corp - Enterprise Upgrade",
  "crm_account_id": "4a1f...-uuid",
  "probability": 60,
  "income": 12000,
  "deadline": "2026-09-01"
}
```

**Reassign an opportunity's owner**

```
POST /crm/opportunities/{id}/do/change-owner
```
```json
{
  "iam_user_id": "3f9c1a20-...-uuid"
}
```

**Create a quote for an opportunity**

```
POST /crm/quotes
```
```json
{
  "crm_opportunity_id": "7e2a...-uuid",
  "name": "Enterprise Upgrade Quote",
  "description": "Quote for the enterprise tier upgrade"
}
```

**Submit a quote for approval, then approve it**

```
POST /crm/quotes/{id}/do/request-approve-quote
POST /crm/quotes/{id}/do/approve
```

**Convert an approved quote to an invoice**

```
POST /crm/quotes/{id}/do/convert-quote-to-invoice
```

**Add a line item to a quote**

```
POST /crm/quote-items
```
```json
{
  "crm_quote_id": "9b3c...-uuid",
  "marketplace_product_id": "5f1d...-uuid",
  "marketplace_product_catalog_id": "2d8e...-uuid",
  "quantity": 2,
  "unit_price": 500,
  "discount": 50
}
```

## Related Features

- [Accounts & Contacts](accounts-and-contacts.md) — opportunities and quotes belong to a CRM account
- [Campaigns & Targeting](campaigns-and-targeting.md) — opportunities can originate from a campaign
- [Performance & Reporting](performance-and-reporting.md) — pipeline performance is tracked over time
