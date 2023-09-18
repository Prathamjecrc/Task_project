<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // app/Models/Task.php

public function user()
{
    return $this->belongsTo(User::class);
}

public function priority()
{
    return $this->belongsTo(Priority::class);
}

}
