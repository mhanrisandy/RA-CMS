<?php

$setting['file'] = 'blob';

if (!empty($_GET['module'])){
	$module = $_GET['module'];
} else {
	$module = '';
}
                            
if (!empty($_GET['act'])){
    $act = $_GET['act'];
} else {
    $act = '';
}
?>