<?php

function kilometersToMiles($km): float
{
    return (float)number_format($km * 0.621371, 2);
}
