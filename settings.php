<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Critique Settings</h2>           
	<form method="post" action="">
		<input type="hidden" name="critique_action" value="critique_update" />

		<h3 class="title">Active Post Types</h3>
		<table class="form-table">
			<p class="description">After enabling Critique in a post type you may need to go to your Screen Options and select it to be displayed. If you disable critique from a post type preview reviews from that type will no longer be shown.</p>
			<tr valign="top">
				<th scope="row">Enable in Posts</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span>Enable in Posts</span></legend>
						<label for="post_types[post]">
							<input type="hidden" name="post_types[post]" value="off" />
							<input name="post_types[post]" type="checkbox" id="post_types[post]" value="on" <?= ($this->settings['post_types']['post']=='on'?'checked':'') ?> >
							Enable critique in blog posts and feeds.
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Enable in Pages</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span>Enable in Posts</span></legend>
						<label for="post_types[page]">
							<input type="hidden" name="post_types[page]" value="off" />
							<input name="post_types[page]" type="checkbox" id="post_types[page]" value="on" <?= ($this->settings['post_types']['page']=='on'?'checked':'') ?> >
							Enable critique in static pages (and feeds of pages).
						</label>
					</fieldset>
				</td>
			</tr>
		</table>

		<h3 class="title">Display Options</h3>
		<table class="form-table">
			<p class="description">These will take effect immediately. Some themes or other plug-ins may effect formatting, if it's looking odd on your site let me know the theme and I'll try and get it looking better.</p>
			<tr valign="top">
				<th scope="row">Show full in short posts</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span>Show full in short posts</span></legend>
						<label for="show_options[full_in_short]">
							<input type="hidden" name="show_options[full_in_short]" value="off" />
							<input name="show_options[full_in_short]" type="checkbox" id="show_options[full_in_short]" value="on" <?= ($this->settings['show_options']['full_in_short']=='on'?'checked':'') ?> >
							Show the full blog review block in excerpts or short posts (like on the blog homepage).
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Show overall average in short posts</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span>Show full in short posts</span></legend>
						<label for="show_options[overall_in_short]">
							<input type="hidden" name="show_options[overall_in_short]" value="off" />
							<input name="show_options[overall_in_short]" type="checkbox" id="show_options[overall_in_short]" value="on" <?= ($this->settings['show_options']['overall_in_short']=='on'?'checked':'') ?> >
							Show only the overall average in excerpts or  short posts (like on teh blog homepage). Overall averages must be enabled below.
						</label>
					</fieldset>
				</td>
			</tr>
		</table>

		<h3 class="title">Review Settings</h3>
		<p class="description">Changes to the review system are not retroactive, so if you go from a 5 Star system to a # out of 100 system critiques already made will still be 5 star. New sections are added, however they will be left blank, blank sections do not show up in the display and do not factor into the overall average.</p>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="scale">Review Scale</label></th>
				<td><select name="scale" id="scale">
					<option value="5-stars" <?= ($this->settings['scale']=='5-stars'?'selected':'') ?> >5 Stars</option>
					<option value="out-of-100" <?= ($this->settings['scale']=='out-of-100'?'selected':'') ?> ># out of 100</option>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="sections">Review Sections</label></th>
				<td>
					<input name="sections" type="text" id="sections" value="<?= $this->settings['sections'] ?>" class="regular-text">
					<p class="description">If you want to review multiple aspects of something (build quality, performance, price, ect.) add each section here in a comma separated list.</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Add Overall Average</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span>Add Overall Average</span></legend>
						<label for="add_average">
							<input type="hidden" name="add_average" value="off" />
							<input name="add_average" type="checkbox" id="add_average" value="on" <?= ($this->settings['add_average']=='on'?'checked':'') ?> >
							When using multiple review sections add an "Overall" item at the bottom that averages each individual section.
						</label>
					</fieldset>
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" id="submit" class="button button-primary" value="Save Changes">
		</p>
	</form>

		<h3 class="title">Miscellaneous</h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="nothing">Have a problem</label></th>
				<td>
					Having trouble getting the plug-in working? Expected results? Feel like direction your repentant rage at someone far far away? Just have a general usage question? You can try the <a href="http://wordpress.org/support/plugin/critique" target="_blank">plug-ins support form</a> or, if you want an answer from the source, feel free to email me at <a href="mailto:phillip.gooch@gmail.com" target="_blank">phillip.gooch@gmail.com</a>.
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="nothing">Check out the code</label></th>
				<td>
					Want to see how it all works, you can check out the on the <a href="http://plugins.svn.wordpress.org/critique/trunk/" target="_blank">WordPress SVN</a> or, even better, <a href="https://github.com/pgooch/critique" target="_blank">fork it on GitHub</a>. Feel free to make changes and submit pull requests, good ideas will be added to the master branch.
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Donate</th>
				<td>
					Like the plug-in and want to support further development? Thanks! You can use the paypal button below to donate any amount you want. Don't like PayPal? send me an email, we can figure something out.<br/>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>

				</td>
			</tr>
		</table>
</div>



