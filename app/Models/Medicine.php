<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Medicine extends Model
{
    protected $fillable = [
        'pharmacy_id','category_id','name','generic_name','brand_name','code',
        'description','composition','type','category_type','strength','unit',
        'manufacturer','supplier','current_stock','reorder_level','reorder_quantity',
        'storage_location','storage_condition','purchase_price','selling_price',
        'mrp','batch_number','expiry_date','hsn_code','tax_percentage','is_active',
    ];
    protected $casts = ['is_active'=>'boolean','expiry_date'=>'date','purchase_price'=>'decimal:2','selling_price'=>'decimal:2'];
    public function pharmacy()  { return $this->belongsTo(Pharmacy::class); }
    public function category()  { return $this->belongsTo(MedicineCategory::class); }
    public function movements() { return $this->hasMany(StockMovement::class); }
    public function isLowStock(): bool { return $this->current_stock <= $this->reorder_level; }
}
