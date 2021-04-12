<?php
/*
** Mancave API
** Coded by Tamer
** 06-12-2017
*/

// Include the Mancave class.
require_once('../mancave.class.php');

// To add a new rolling shutter use:
$c = new Mancave_Controller();
$c->devices_Add('rolluik', 'name-of-the-shutter'); // this will return the unique device ID.
