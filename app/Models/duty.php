<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class duty extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = ['title','content','start','end','status','user_id'];

    public function flight()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
