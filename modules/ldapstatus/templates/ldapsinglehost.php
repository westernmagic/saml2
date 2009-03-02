<?php
$this->data['header'] = 'LDAP status page';
$this->data['head'] = '<style>
table.statustable td {
	border-bottom: 1px solid #eee;
}
a {
	color: #333;
	text-decoration: none;
	border-bottom: 1px dotted #aaa;
}
a:hover {
	border-bottom: 1px solid #aaa;
}
div#content {
	margin: .4em ! important;
}
p {
	margin: 1em 0px 2px 0px
}
div.inbox p { margin: 0; }


</style>';
$this->includeAtTemplateBase('includes/header.php');

?>

<h2>LDAP test for <?php echo $this->getTranslation($this->data['org']['description']); ?></h2>

<p>[ <a href="?">return to list of all organizations</a> ]</p>

<?php

$t = $this;

function presentRes($restag) {

	global $t;
	echo('<div class="inbox" style="border: 1px solid #aaa; background: #eee; padding: .4em; margin: .2em;">');
	
	if (array_key_exists($restag, $t->data['res'])) {
		$res = $t->data['res'][$restag];
		if ($res[0]) {	
			echo('<img style="float: right" src="/' . $t->data['baseurlpath'] . 'resources/icons/accept.png" />');
			echo('OK: ' . $res[1]);
		} else {
			echo('<img style="float: right" src="/' . $t->data['baseurlpath'] . 'resources/icons/gn/stop-l.png" />');
			echo($res[1]);
		}
		echo('<div style="clear: both; height: 0px"></div>');
	} else {
		echo('<p style="color: #ccc">NA</p>');
	}
	echo('</div>');
}

function presentCertRes($restag) {

	global $t;
	echo('<div class="inbox" style="border: 1px solid #aaa; background: #eee; padding: .4em; margin: .2em;">');
	
	if (array_key_exists($restag, $t->data['res'])) {
		$res = $t->data['res'][$restag];
		if ($res[0]) {	
			echo('<img style="float: right" src="/' . $t->data['baseurlpath'] . 'resources/icons/accept.png" />');
			echo('OK: ' . $res[1]);
		} else {
			echo('<img style="float: right" src="/' . $t->data['baseurlpath'] . 'resources/icons/gn/stop-l.png" />');
			echo($res[1]);
		}
		
		if (isset($res['expire'])) {
			echo('<p>Certificate expires in ' . $res['expire'] . ' days</p>');
		}
		if (isset($res['expireText'])) {
			echo('<p>Certificate expires on ' . $res['expireText'] . '</p>');
		}
		
		echo('<div style="clear: both; height: 0px"></div>');
	} else {
		echo('<p style="color: #ccc">NA</p>');
	}
	echo('</div>');
}

$ok = TRUE;
foreach ($this->data['res'] AS $tag => $res) {
	if ($tag == 'time') continue;
	if ($res[0] == 0)  $ok = FALSE;
#	echo ('failed: ' . $tag . '[' . $res[0] . ']'); }
}

if (array_key_exists('secretURL', $this->data)) {
	
	echo('<p>This page can be accessed by this secret URL:<br />');
	echo('<pre  style="border: 1px solid #aaa; background: #eee; color: #999;c padding: .1em; margin: .2em;">');
	echo(htmlentities($this->data['secretURL']));
	echo('</pre></p>');
	
}

echo('<p>Status:</p>');
if ($ok) {
	echo('<div class="inbox" style="border: 1px solid #aaa; background: yellow; padding: .4em; margin: .2em;">');
	echo('<img style="float: right" src="/' . $t->data['baseurlpath'] . 'resources/icons/gn/success-l.png" />');
	echo('All checks was OK');
	echo('<div style="clear: both; height: 0px"></div>');
	echo('</div>');
} else {
	echo('<div class="inbox" style="border: 1px solid #aaa; background: yellow; padding: .4em; margin: .2em;">');
	echo('<img style="float: right" src="/' . $t->data['baseurlpath'] . 'resources/icons/gn/stop-l.png" />');
	echo('At least one test failed.');
	echo('<div style="clear: both; height: 0px"></div>');
	echo('</div>');	
}


?>
<p>Checking configuration if all parameters are set properly.</p>
<?php presentRes('config'); ?>

<p>Trying to setup a TCP socket against the LDAP host.</p>
<?php presentRes('ping'); ?>

<p>Check certificate.</p>
<?php presentCertRes('cert'); ?>


<p>Trying to bind() with the LDAP admin user.</p>
<?php presentRes('adminBind'); ?>

<p>Trying to search LDAP with a bogus user (should return zero results, and no error)</p>
<?php presentRes('ldapSearchBogus'); ?>

<p>Is a test user defined?</p>
<?php presentRes('configTest'); ?>

<p>Search LDAP for the DN of the test user given a specific eduPersonPrincipalName</p>
<?php presentRes('ldapSearchTestUser'); ?>

<p>Trying to bind() as the DN found when searching for the test user</p>
<?php presentRes('ldapBindTestUser'); ?>

<p>Getting attributes from referred eduOrgDN and eduOrgUnitDN (from test user)</p>
<?php presentRes('getTestOrg'); ?>

<p>Checking for additional contact addresss in configuration.</p>
<?php presentRes('configMeta'); ?>



<h2>Debug log</h2>
<pre style="background: #eee; border: 1px solid #aaa">
<?php echo join("\n", $this->data['debugLog']); ?>
</pre>


<?php $this->includeAtTemplateBase('includes/footer.php'); ?>