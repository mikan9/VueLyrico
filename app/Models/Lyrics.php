<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lyrics extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lyrics';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}