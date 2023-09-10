<?php

namespace App\Imports;

use App\Models\Supplier;
use App\Models\SupplierLedger;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\SupplierUtil;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToCollection;

class SupplierImport implements ToCollection
{
    protected $supplierUtil;
    protected $invoiceVoucherRefIdUtil;
    protected $branch_id;
    /**
     * @param Collection $collection
     */

    public function __construct(Request $request)
    {
        $this->branch_id = $request->add_branch_id;
    }
    public function collection(Collection $collection)
    {
        $this->invoiceVoucherRefIdUtil = new InvoiceVoucherRefIdUtil;

        $index = 0;
        $generalSettings = DB::table('general_settings')->first('prefix');
        $supIdPrefix = json_decode($generalSettings->prefix, true)['supplier_id'];
        $this->supplierUtil = new SupplierUtil();
        $branch = DB::table('branches')->where('id', $this->branch_id)->select('id', 'name', 'branch_code')->first();
        $branchUser = getBranchUser($this->branch_id);

        foreach ($collection as $c) {
            if ($index != 0) {
                if ($c[2]) {

                    $firstLetterOfSupplier = str_split($c[2])[0];
                    $supplierId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('suppliers'), 4, "0", STR_PAD_LEFT);

                    $addSupplier = Supplier::create([
                        'admin_user_id' => $branchUser->id,
                        'branch_id' => $this->branch_id,
                        'contact_id' => $c[0] ? $c[0] : $supIdPrefix . $supplierId,
                        'business_name' => $branch->name,
                        'name' => $c[2],
                        'phone' => $c[3],
                        'alternative_phone' => $c[4],
                        'landline' => $c[5],
                        'email' => $c[6],
                        'date_of_birth' => $c[7],
                        'tax_number' => $c[8],
                        'opening_balance' => (float)$c[9] ? (float)$c[9] : 0,
                        'address' => $c[10],
                        'city' => $c[11],
                        'state' => $c[12],
                        'country' => $c[13],
                        'zip_code' => $c[14],
                        'shipping_address' => $c[15],
                        'prefix' => $c[16] ? $c[16] : $firstLetterOfSupplier . $supplierId,
                        'pay_term_number' => (float)$c[17],
                        'pay_term' => (float)$c[18],
                        'total_purchase_due' => (float)$c[9] ? (float)$c[9] : 0,
                    ]);

                    // Add supplier Ledger
                    $this->supplierUtil->addSupplierLedger(
                        voucher_type_id: 0,
                        supplier_id: $addSupplier->id,
                        branch_id: $this->branch_id,
                        date: date('Y-m-d'),
                        trans_id: NULL,
                        amount: (float)$c[9] ? (float)$c[9] : 0
                    );
                }
            }
            $index++;
        }
    }
}
