<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function section() {

        return $this->belongsTo(Section::class, 'section_id');

    }

    public function invoiceAttachments() {

        return $this->hasMany(Invoice_Attachments::class);

    }

    public function invoiceDetails() {

        return$this->hasMany(Invoice_Details::class);

    }

}
