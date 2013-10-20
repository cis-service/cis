<?php
/*
 * unbenannt.php
 * 
 * Copyright 2013 Lukas Plattner <voxel@cube>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Client Example</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.22" />
</head>

<body>
    <?php
    include 'Client.php';
    $client = new Cis\Client();
    /*mkdir("test");
    chdir(__DIR__."/../");
    mkdir("data/origial/d4/1d/8c/d9/8f/00/b2/04/e9/80/09/98/ec/f8/42",0775,true);*/
    
    var_dump($client->saveFromPath('dragonV2.png','Avatar','Voxel',3));
    ?>
	
</body>

</html>
