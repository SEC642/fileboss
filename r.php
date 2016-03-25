<?php
/* Retrieve a file for the, send to the requester */

if (isset($_POST['rname'])) {

	$fname = "upload/".basename($_POST["rname"]);
	if (file_exists("$fname")) {
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.basename($fname).".enc");
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($fname));
	    ob_clean();
	    flush();

	    readfile($fname);
	} else {
        echo "<html><head>Sorry</head><body>Sorry, that file does not exist.  Please click back and try a different filename.</body></html>";
    }

} else {
	header( 'Location: /' ) ;
}
?>
