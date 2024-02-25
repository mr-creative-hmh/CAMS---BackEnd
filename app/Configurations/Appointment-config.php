<?php

// namespace
namespace App\Configurations;

enum APPOINTMENT_TYPE : string
{
    case Normal = "Normal";
    case FollowUP = "Follow-Up";
    case ReScheduled = "Re-Scheduled";
}


enum APPOINTMENT_STATUS : string
{
    case Scheduled = "Scheduled";
    case Cancelled = "Cancelled";
    case Completed = "Completed";
}


