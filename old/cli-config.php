<?php

use App\DataAccess;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use App\Services\EntityService as Entities;

require __DIR__ . '/src/Globals.php';

return ConsoleRunner::createHelperSet(Entities::em());