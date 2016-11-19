<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model {
    protected $table = 'challenge';
    public function test() {
    	if($this->difficulty == 1) {
    		return "Easy";
    	} else if($this->difficulty == 2) {
    		return "Moderate";
    	} else if($this->difficulty == 3) {
    		return "Difficult";
    	} else {
    		return "Unknown";
    	}
    }
}