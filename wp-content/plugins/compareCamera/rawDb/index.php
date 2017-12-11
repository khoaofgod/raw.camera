<?php

namespace rawDb;

require_once __DIR__ . "/../libs.php";

$remote = new remote();
$remote->remoteImportAll();
