<?php

namespace App\Models;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Models\InvoiceLayout;
use App\Models\InvoiceSchema;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function invoice_schema()
    {
        return $this->belongsTo(InvoiceSchema::class, 'invoice_schema_id');
    }

    public function add_sale_invoice_layout()
    {
        return $this->belongsTo(InvoiceLayout::class, 'add_sale_invoice_layout_id', 'id');
    }

    public function pos_sale_invoice_layout()
    {
        return $this->belongsTo(InvoiceLayout::class, 'pos_sale_invoice_layout_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}

