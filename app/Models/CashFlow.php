<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Hrm\Payroll;
use App\Models\SalePayment;
use App\Models\AdminAndUser;
use App\Models\MoneyReceipt;
use App\Models\ExpensePayment;
use App\Models\PurchasePayment;
use App\Models\Hrm\PayrollPayment;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function sender_account()
    {
        return $this->belongsTo(Account::class, 'sender_account_id');
    }

    public function receiver_account()
    {
        return $this->belongsTo(Account::class, 'receiver_account_id');
    }

    public function sale_payment()
    {
        return $this->belongsTo(SalePayment::class, 'sale_payment_id');
    }

    public function purchase_payment()
    {
        return $this->belongsTo(PurchasePayment::class, 'purchase_payment_id');
    }

    public function expense_payment()
    {
        return $this->belongsTo(ExpensePayment::class, 'expense_payment_id');
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    public function payroll_payment()
    {
        return $this->belongsTo(PayrollPayment::class, 'payroll_payment_id');
    }

    public function money_receipt()
    {
        return $this->belongsTo(MoneyReceipt::class, 'money_receipt_id');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function loan_payment()
    {
        return $this->belongsTo(LoanPayment::class, 'loan_payment_id');
    }

    public function supplier_payment()
    {
        return $this->belongsTo(SupplierPayment::class, 'supplier_payment_id');
    }

    public function customer_payment()
    {
        return $this->belongsTo(CustomerPayment::class, 'customer_payment_id');
    }

    public function admin()
    {
        return $this->belongsTo(AdminAndUser::class, 'admin_id');
    }
}
