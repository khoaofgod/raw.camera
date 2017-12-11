<?php

namespace rawDb;

require_once (__DIR__."/_header.php");
require_once (__DIR__."/../rawDb/remote.php");


$remote = new remote();
$remote->remoteImportAll();



require_once (__DIR__."/_bottom.php");
