<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('fund-duplicity', function () {
    return true;
});