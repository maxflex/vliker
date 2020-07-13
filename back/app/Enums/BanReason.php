<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class BanReason extends Enum implements LocalizedEnum
{
    const Report = 'report';
    const Cheat = 'cheat';
}
