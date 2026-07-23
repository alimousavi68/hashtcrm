<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'is_encrypted',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    /**
     * Get value with automatic decryption if encrypted.
     */
    public function getValueAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        if ($this->is_encrypted) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return $value;
            }
        }

        return $value;
    }

    /**
     * Set value with automatic encryption if marked as encrypted.
     */
    public function setValueAttribute($value)
    {
        if ($this->is_encrypted && !empty($value)) {
            try {
                $this->attributes['value'] = Crypt::encryptString($value);
                return;
            } catch (\Exception $e) {
                $this->attributes['value'] = $value;
                return;
            }
        }

        $this->attributes['value'] = $value;
    }
}
