<?php

$con = mysqli_connect("localhost", "root", "", "api");
if(!$con) 
{
    die("connection failed");
}