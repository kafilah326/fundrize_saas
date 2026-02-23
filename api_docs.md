# API Documentation

This document contains the specifications for the available API endpoints in this Fundrize application.

## Base Information
- **Base URL**: `[YOUR_APP_URL]/api`
- **Authentication**: All endpoints are protected by the `api.auth` middleware. You must pass the required authentication header/token as defined by your system's `SimpleApiAuth` implementation.

---

## 1. Get Maintenance Fees

Retrieves a list of generated maintenance fees, aggregated per month for a specific year.

- **Endpoint**: `/maintenance-fees`
- **Method**: `GET`
- **Middleware**: `api.auth`

### Query Parameters

| Parameter | Type | Required | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `year` | integer | No | Current Year | The year to filter the maintenance fees. |

### Success Response Example

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "year": 2024,
      "month": 3,
      "month_name": "Maret",
      "total_amount": 15000000,
      "fee_amount": 300000,
      "status": "pending",
      "proof_of_payment": null,
      "paid_at": null,
      "created_at": "2024-03-01T10:00:00.000000Z",
      "updated_at": "2024-03-01T10:00:00.000000Z"
    }
  ]
}
```

---

## 2. Update Maintenance Fee Status

Updates the payment status of a specific maintenance fee record.

- **Endpoint**: `/maintenance-fees/{id}/status`
- **Method**: `PUT`
- **Middleware**: `api.auth`

### URL Parameters

| Parameter | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `id` | integer | Yes | The ID of the maintenance fee record. |

### Request Body (JSON)

| Parameter | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `status` | string | Yes | The new status. Must be one of: `pending`, `unverified`, `paid`. |

### Success Response Example

```json
{
  "success": true,
  "message": "Status updated successfully",
  "data": {
    "id": 1,
    "year": 2024,
    "month": 3,
    "status": "paid",
    "paid_at": "2024-03-15T14:30:00.000000Z"
  }
}
```

---

## 3. Get All Transactions

Retrieves a combined and chronological list of all categorized transactions (`Donasi`, `Qurban`, `Tabungan Qurban`).

- **Endpoint**: `/transactions`
- **Method**: `GET`
- **Middleware**: `api.auth`

### Query Parameters

| Parameter | Type | Required | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `start_date` | string (YYYY-MM-DD) | No | null | Filter transactions starting from this date. |
| `end_date` | string (YYYY-MM-DD) | No | null | Filter transactions up to this date. |
| `limit` | integer | No | `500` | The maximum number of records to fetch per category if no date filter is applied. Prevent memory exhaustion. |

*(Note: If both `start_date` and `end_date` are provided, the `limit` parameter is ignored and all matching records within the range are returned).*

### Success Response Example

```json
{
  "success": true,
  "data": [
    {
      "transaction_id": "TRX-12345",
      "type": "Donasi",
      "title": "Donasi: Program Pembangunan Masjid",
      "amount": 500000,
      "fee_maintenance": 10000,
      "status": "success",
      "customer_name": "Budi Santoso",
      "created_at": "2024-03-25T08:15:00.000000Z"
    },
    {
      "transaction_id": "QRB-67890",
      "type": "Qurban",
      "title": "Qurban: Sapi Tipe A",
      "amount": 2500000,
      "fee_maintenance": 50000,
      "status": "paid",
      "customer_name": "Hamba Allah",
      "created_at": "2024-03-24T10:00:00.000000Z"
    }
  ]
}
```
