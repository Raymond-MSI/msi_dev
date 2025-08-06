<?php 
$property = 1;
include($components . $file);
$fstat = stat($components . $file);
$properties = array(
	"version"=>$version, 
	"released"=>strtotime(date("d F Y H:i:s", $fstat['mtime'])), 
	"author"=>$author, 
	"created"=>date("d F Y H:i:s", $fstat['ctime']),
	"modified"=>date("d F Y H:i:s", $fstat['mtime']),
	"atime" => date("d F Y H:i:s", $fstat['atime']),
	"mtime" => date("d F Y H:i:s", $fstat['mtime']),
	"ctime" => date("d F Y H:i:s", $fstat['ctime']),
	"dev" => $fstat['dev'],
	"ino" => $fstat['ino'],
	"mode" => $fstat['mode'],
	"nlink" => $fstat['nlink'],
	"uid" => $fstat['uid'],
	"gid" => $fstat['gid'],
	"rdev" => $fstat['rdev'],
	"size" => $fstat['size'],
	"blksize" => $fstat['blksize'],
	"blocks" => $fstat['blocks']
);
?>
