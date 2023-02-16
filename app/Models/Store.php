<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $fillable = ['branch_name','address','openingCash','vat','contact','trade_license','main_branch','invoice_header_text','invoice_contact','invoice_email','invoice_footer_text','invoice_logo'];
    public function account () {
        return $this->hasOne('App\Models\CashAccount', 'store_id','id')->where('is_main',1)->select('id','account_name','store_id');
      }
}
