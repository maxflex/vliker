<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class TaskType extends Enum implements LocalizedEnum
{
    const Subscribe = 'subscribe';
    const Like = 'like';
    const Comment = 'comment';
}
