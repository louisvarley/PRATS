<?php
namespace App\Enums;

enum RatStatus: string {
    case Alive 			= 'A';
    case Deceased 		= 'D';
    case Unknown 		= 'U';
}