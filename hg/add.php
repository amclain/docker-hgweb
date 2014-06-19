<?php
	//////////////////////////////////
	//  Add A Mercurial Repository  //
	//////////////////////////////////
	//  Version 1.0.2
	//  http://apps.alexmclain.com
	
	// USER SETTINGS:
	
	$hgpath = "/var/hg/repos/"; //File path to Mercurial repositories.
	
	// END USER SETTINGS ////////////////////////////////////////////////////
	
	$backLink = "<a href=''>Back</a>";

	if ($_REQUEST['Submit'] == "Submit" && !empty($_REQUEST['folder'])) {
		$repoFolder = trim(strtolower($_REQUEST['folder']));
		
		if (strpos($repoFolder," ") || strpos($repoFolder,"\t")) {
			//Folder name contains a space.
			print("Folder name contains whitespace.<br>\n");
			print($backLink);
			return;
		}
		
		if (file_exists($hgpath.$repoFolder)) {
			//Repository already exists.
			print("Repository exists.<br>\n");
			print($backLink);
			return;
		}
		
		if (!is_writable($hgpath)) {
			//Repo path is not writable.
			print("Repository path is not writable.<br>\n");
			print($backLink);
			return;
		}
		
		//Initialize repository.
		system("hg init ".$hgpath.$repoFolder);
		
		//Write HGRC file.
		$text  = "[web]\n";
		if (!empty($_REQUEST['name'])) {$text .= "name = ".trim($_REQUEST['name'])."\n";}
		if (!empty($_REQUEST['contact'])) {$text .= "contact = ".trim($_REQUEST['contact'])."\n";}
		$text .= "description = ".trim($_REQUEST['description'])."\n";
		if (!empty($_REQUEST['allow'])) {$text .= "allow_push = ".$_REQUEST['allow']."\n";}
		
		$fs = fopen($hgpath.$repoFolder."/.hg/hgrc","w");
		fwrite($fs,$text);
		
		print("Created repository.");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Mercurial Repository</title>
<style type="text/css">
<!--
body {
	font-family: Arial;
	font-size: 12px;
}
h1 {
	font-family: Arial;
	font-size: 16px;
}
-->
</style>
</head>

<body>
<h1>Add Mercurial Repository</h1>
<form id="form1" name="form1" method="post" action="">
  <table border="0" cellspacing="0" cellpadding="4">
    <tr>
      <td>Folder:</td>
      <td><input type="text" name="folder" id="folder" /></td>
    </tr>
    <tr>
      <td>Repo Name:</td>
      <td><input type="text" name="name" id="name" /></td>
    </tr>
    <tr>
      <td>Description:</td>
      <td><input type="text" name="description" id="description" /></td>
    </tr>
    <tr>
      <td>Contact:</td>
      <td><input type="text" name="contact" id="contact" /></td>
    </tr>
    <tr>
      <td>Allow:</td>
      <td><input type="text" name="allow" id="allow" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><em>Leave allow blank for server push defaults.</em></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" id="Submit" value="Submit" /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>
