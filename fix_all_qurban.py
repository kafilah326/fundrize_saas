import os
import re

print("1. Fixing QurbanOrder.php")
f1 = 'app/Models/QurbanOrder.php'
with open(f1, 'r') as f:
    c1 = f.read()
if "'total'," not in c1:
    c1 = c1.replace("'amount',", "'amount',\n        'total',")
    c1 = c1.replace("'amount' => 'decimal:2',", "'amount' => 'decimal:2',\n        'total' => 'decimal:2',")
    with open(f1, 'w') as f:
        f.write(c1)

print("2. Fixing QurbanSavingsDeposit.php")
f2 = 'app/Models/QurbanSavingsDeposit.php'
with open(f2, 'r') as f:
    c2 = f.read()
if "'total'," not in c2:
    c2 = c2.replace("'amount',", "'amount',\n        'total',")
    c2 = c2.replace("'amount' => 'decimal:2',", "'amount' => 'decimal:2',\n        'total' => 'decimal:2',")
    with open(f2, 'w') as f:
        f.write(c2)

print("3. Fixing PaymentMethod.php")
f3 = 'app/Livewire/Front/PaymentMethod.php'
with open(f3, 'r') as f:
    c3 = f.read()

# For QurbanOrder
block1 = """QurbanOrder::create([
            'transaction_id' => $transactionId,"""
if "'total' => $finalTotal" not in c3.split("QurbanSavingsDeposit::create")[0]:
    c3 = c3.replace(
        "'amount'         => $this->amount,\n            'payment_method'",
        "'amount'         => $this->amount,\n            'total'          => $finalTotal,\n            'payment_method'"
    )

with open(f3, 'w') as f:
    f.write(c3)

print("Done python script.")
