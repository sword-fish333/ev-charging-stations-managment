<?php

function fullLog($log_message){
    $log_message .= ' method invoked:' . debug_backtrace()[1]['function'] . ' on route: ' . \Route::currentRouteAction();
    info($log_message);
}
