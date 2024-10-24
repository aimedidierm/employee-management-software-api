<?php

namespace App\Enums;

enum EmployeePosition: string
{
    case DEVELOPER = 'Developer';
    case MANAGER = 'Manager';
    case ENGINEER = 'Engineer';
    case DESIGNER = 'Designer';
}
