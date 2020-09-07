<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DonationOrder extends Model
{

    protected $fillable = [
        'donor_name',
        'status',
        'tracking_id',
        'donor_email', 'msisdn', 'amount','order_id'
    ];
}
