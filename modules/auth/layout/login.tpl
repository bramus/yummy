<div class="box" id="boxLogin">
	
	<h2>Please enter your login credentials</h2>


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
	
	
	<div class="boxInner">
		<form action="{$formUrl|htmlentities}" method="post">
			<fieldset>
				<dl class="clearfix columns">
					<dt class="column column-26"><label for="username">Username:</label></dt>
					<dd class="column column-46"><input type="text" name="username" id="username" value="{$username|htmlentities}" /></dd>
					<dt class="column column-26"><label for="password">Password:</label></dt>
					<dd class="column column-46"><input type="password" name="password" id="password" value="{$password|htmlentities}" /></dd>
					<dd class="column column-16 padd-26" id="col-submit">
						<label for="btnSubmit"><input type="submit" id="btnSubmit" name="btnSubmit" value="Log in" /></label>
						<input type="hidden" name="from" id="from" value="{$from|htmlentities}" />
						<input type="hidden" name="formAction" id="formAction" value="login" />
					</dd>
				</dl>
			</fieldset>
		</form>
	</div>
	
</div>