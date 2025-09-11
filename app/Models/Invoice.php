<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';

    const STATUS_CONFIRMED = 'مدفوعة';

    const STATUS_PENDING = 'معلقة';

    const STATUS_CANCELLED = 'ملغاة';


    protected $fillable = [
        'invoice_number', 'invoice_date', 'customer_id', 'branch_id', 'company_id','session_year',
        'employee_id', 'invoice_type', 'parent_invoice_id', 'subtotal', 'discount','supplier_id',
        'tax', 'total', 'status', 'payment_method', 'bank_account_id'
    ];

    public static function generateEntryNumber($companyId)
    {
        $lastInvoice = self::where('company_id', $companyId)
            ->orderBy('id', 'desc')
            ->first();
        $nextNumber = $lastInvoice ? intval($lastInvoice->invoice_number) + 1 : 1;
        while (self::where('company_id', $companyId)
            ->where('invoice_number', str_pad($nextNumber, 7, '0', STR_PAD_LEFT))
            ->exists()) {
            $nextNumber++;
        }
        return str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function sessionYear()
    {
        return $this->belongsTo(SessionYear::class , 'session_year');
    }
    public  function items(){
        return $this->hasMany(InvoiceItem::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class , 'customer_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class , 'supplier_id');
    }

}
