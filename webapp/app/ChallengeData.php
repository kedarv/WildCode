<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class ChallengeData extends Model {
    protected $table = 'challenge_data';
    protected $fillable = ['user_id', 'challenge_id', 'created_at', 'updated_at'];
}