<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GuestbookMessage extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'message', 'image', 'ip'];

    public static $editMinutes = 5;

    public function isEditable($editorIp): bool
    {
        $now = new \DateTime(now());
        $created = new \DateTime($this->created_at);
        $minutes = abs($now->getTimestamp() - $created->getTimestamp()) / 60;

        if($this->ip === $editorIp &&
            $minutes < self::$editMinutes){
            return true;
        }

        return false;
    }
}
