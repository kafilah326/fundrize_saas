# Plan: Zakat API Integration

This plan outlines the steps to integrate `ZakatTransaction` data into the existing API endpoints in `ApiController`.

## 1. Grounding & Context
- **Target File**: `app/Http/Controllers/ApiController.php`
- **Model**: `App\Models\ZakatTransaction`
- **Business Logic**:
    - Zakat transactions are subject to the standard `SYSTEM_FEE_PERCENTAGE`.
    - Successful Zakat transaction status is `'success'`.
- **Affected Endpoints**:
    - `getMaintenanceFees`: Monthly totals must include Zakat.
    - `getTransactions`: List must include Zakat records.

## 2. Technical Approach
### getMaintenanceFees Integration
- In the monthly loop, add a query to sum `amount` from `ZakatTransaction` where the month and year match, and status is `'success'`.
- Add this sum to `$totalCollected`.

### getTransactions Integration
- Fetch `ZakatTransaction` records using the same date filtering and limit logic as other transaction types.
- Map the records to a consistent structure:
    - `transaction_id`: `$z->transaction_id`
    - `type`: `'Zakat'`
    - `title`: `'Zakat: ' . $z->zakat_type_label` (using accessor)
    - `amount`: `$z->amount`
    - `fee_maintenance`: calculated using `$feePercentage`
    - `status`: `$z->status`
    - `customer_name`: `$z->donor_name`
    - `created_at`: `$z->created_at`
- Merge the mapped collection into the `$all` collection.

## 3. Implementation Tasks

### Task 1: Update getMaintenanceFees
- [ ] Add `use App\Models\ZakatTransaction;` to imports.
- [ ] Inside `getMaintenanceFees` loop:
    - Query `ZakatTransaction` for `totalZakat` based on month, year, and `'success'` status.
    - Update `$totalCollected` calculation: `$totalCollected = $totalDonations + $totalQurban + $totalSavings + $totalZakat;`.

### Task 2: Update getTransactions
- [ ] Inside `getTransactions` method:
    - Implement `$zakatQuery` following the pattern of `$donationQuery` (latest, date filtering, limit).
    - Map `$zakatQuery->get()` results to the standard transaction format.
    - Use `$z->zakat_type_label` for the title.
    - Merge mapped zakat transactions into the final `$all` collection.

## 4. Final Verification Wave
- [ ] **QA 1**: Verify `getMaintenanceFees` includes Zakat amounts in the `total_amount` field.
- [ ] **QA 2**: Verify `getTransactions` returns Zakat records with correct `type` and `title`.
- [ ] **QA 3**: Ensure date filtering (`start_date`, `end_date`) applies correctly to Zakat transactions.
- [ ] **QA 4**: Confirm system fee calculation for Zakat matches expectations.
