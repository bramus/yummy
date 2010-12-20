<div class="box" id="boxUpload">
	<h2>Install Yummy! by uploading a del.icio.us export</h2>

{option:oErrors}
	<div class="box" id="boxError">
		<p>One or more errors were encountered:</p>
		<ul class="errors">
			{iteration:iErrors}
			<li>{$error|htmlentities}</li>
			{/iteration:iErrors}
		</ul>	
	</div>
{/option:oErrors}

	<form action="{$formUrl|htmlentities}" method="post" enctype="multipart/form-data">
		<fieldset>
			<dl class="clearfix columns">
				<dt class="column column-26"><label for="file">Exported file:</label></dt>
				<dd class="column column-46"><input type="file" name="file" id="file" value="" /></dd>
				<dd class="column column-16 padd-26" id="col-submit">
					<label for="btnSubmit"><input type="submit" id="btnSubmit" name="btnSubmit" value="Upload" /></label>
					<input type="hidden" name="formAction" id="formAction" value="upload" />
				</dd>
			</dl>
		</fieldset>
	</form>
	{option:linksFound}
<p class="warning">Installation has been completed before. Installing again will erase all existing links!</p>
{/option:linksFound}
	<p class="info">You can create an export of your bookmarks at <a href="http://www.delicious.com/settings/bookmarks/export">http://www.delicious.com/settings/bookmarks/export</a></p>
</div>
{*
<div class="box" id="boxImport">
	<h2>Install Yummy! by entering your del.icio.us credentials</h2>
	<p>Soon!</p>
</div>
*}