<?php

namespace App\Enums;

enum CharacterRoleType: string
{
    case Main      = 'main';
    case Secondary = 'secondary';
    case Episodic  = 'episodic';
}
