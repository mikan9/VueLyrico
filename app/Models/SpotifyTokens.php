<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpotifyTokens extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tokens';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';
}