<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location_Model extends Model
{
    use HasFactory;
    protected $table = 'locations';

    public function user_name_get()
    {
        // return $this->belongsTo(DeliveryHistory::class, 'deliveryman_id');
        return $this->hasOne(User::class, 'tocken', 'user');
    }

    public function category_name()
    {
        // return $this->belongsTo(DeliveryHistory::class, 'deliveryman_id');
        return $this->hasOne(Category_Model::class, 'id', 'category');
    }
}
