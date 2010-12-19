<h2>Yummy! must do a few checks before installation can begin</h2>

<ul class="prerequisites">
	<li{option:oConfigUsername} class="ok"{/option:oConfigUsername}>Define Username & Password in <code>core/includes/config.php</code></li>
	<li{option:oConfigDatabase} class="ok"{/option:oConfigDatabase}>Define valid Database credentials in <code>core/includes/config.php</code></li>
	<li{option:oConfigSql} class="ok"{/option:oConfigSql}>Import <code>yummy.sql</code> onto your database server</li>
</ul>

{option:oConfigOk}
<p class="prerequisites">Everything seems OK, <a href="index.php?module=install&amp;view=install">Proceed with installation &raquo;</a></p>
{/option:oConfigOk}

{option:oConfigNotOk}
<p class="prerequisites">Please fix the errors above before continuing installation.</p>
{/option:oConfigNotOk}