<?php
	if (empty($skin)) $skin='bsd12.bs5';
	if (file_exists("datafile/style/".basename($skin))) include("datafile/style/".basename($skin));
	else include("datafile/style/bsd12.bs5");
