<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryAPI extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','api_key','created_by','updated_by'];

}
