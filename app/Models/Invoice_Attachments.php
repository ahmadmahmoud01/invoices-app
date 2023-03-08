<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_Attachments extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function invoice() {

        return $this->belongsTo(Invoice::class);

    }
}
