<?php
namespace App\Enums;

enum UserRoles: string {
    case Administrator 	= 'A';
    case Breeder 		= 'B';
    case Owner 			= 'O';
    case User 			= 'U';	
}