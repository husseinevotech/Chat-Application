<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
    public function message() {
        return $this->belongsTo(Message::class);
    }

    public function message_group() {
        return $this->belongsTo(MessageGroup::class);
    }
}
