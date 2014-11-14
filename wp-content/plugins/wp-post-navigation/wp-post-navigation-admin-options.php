<style type="text/css">
a.company{
	text-decoration: none;
	font: 600 16px sens-sarif, arial, verdana;
	color: #ff2f2f;
}
select{
	padding: 3px;
	min-width: 70px;
}
input[type="text"]{
	width: 220px;
}
input,
textarea{
	outline: none;
}
.wp-social-box{
	float: left;
	width: 550px;
	background-color: whiteSmoke;
	background-image: -ms-linear-gradient(top,#F9F9F9,whiteSmoke);
	background-image: -moz-linear-gradient(top,#F9F9F9,whiteSmoke);
	background-image: -o-linear-gradient(top,#F9F9F9,whiteSmoke);
	background-image: -webkit-gradient(linear,left top,left bottom,from(#F9F9F9),to(whiteSmoke));
	background-image: -webkit-linear-gradient(top,#F9F9F9,whiteSmoke);
	background-image: linear-gradient(top,#F9F9F9,whiteSmoke);
	border-color: #DFDFDF;
	-moz-box-shadow: inset 0 1px 0 #fff;
	-webkit-box-shadow: inset 0 1px 0 white;
	box-shadow: inset 0 1px 0 white;
	-webkit-border-radius: 3px;
	webkit-border-radius: 3px;
	border-radius: 3px;
	border-width: 1px;
	border-style: solid;
	position: relative;
	margin-bottom: 20px;
	padding: 0;
	border-width: 1px;
	border-style: solid;
	line-height: 1;
	margin-left: 10px;
}
.wp-social-box h3 {
	font-size: 15px;
	font-weight: normal;
	padding: 7px 10px;
	margin: 0;
	line-height: 1;
	font-family: Georgia,"Times New Roman","Bitstream Charter",Times,serif;
	cursor: move;
	-webkit-border-top-left-radius: 3px;
	-webkit-border-top-right-radius: 3px;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	color: #464646;
	border-bottom-color: #DFDFDF;
	text-shadow: white 0 1px 0;
	-moz-box-shadow: 0 1px 0 #fff;
	-webkit-box-shadow: 0 1px 0 white;
	box-shadow: 0 1px 0 white;
	background-color: #F1F1F1;
	background-image: -ms-linear-gradient(top,#F9F9F9,#ECECEC);
	background-image: -moz-linear-gradient(top,#F9F9F9,#ECECEC);
	background-image: -o-linear-gradient(top,#F9F9F9,#ECECEC);
	background-image: -webkit-gradient(linear,left top,left bottom,from(#F9F9F9),to(#ECECEC));
	background-image: -webkit-linear-gradient(top,#F9F9F9,#ECECEC);
	background-image: linear-gradient(top,#F9F9F9,#ECECEC);
	margin-top: 1px;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	cursor: move;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
}
input[type="submit"]{
	cursor: pointer;
}
</style>
<script>
 jQuery(function($){
	
 });
</script>
<div class="wrap" style="margin-top: 30px;margin-left: 10px;max-width:800px !important;">
<div style="width: 570px;float:left;">
<form action="<?php echo $action_url ?>" method="post">
		<input type="hidden" name="submitted" value="1" />
		<?php wp_nonce_field('wp-post-navigation-by-sharp-coders'); ?>
	
<div class="wp-social-box">
	<h3>Configuration</h3>
		<table class="form-table">
			<tbody>
			<tr valign="top">
					<th scope="row" style="width: 100px;">Active</th>
					<td>
						<fieldset>
							<legend class="hidden">Active</legend>
							<label for="is_active"><input type="checkbox" name="is_active" value="1" <?php echo $settings['is_active']=="1"? 'checked="checked"': '' ; ?>  /> (auto place) </label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Position</th>
					<td>
						<fieldset>
							<legend class="hidden">Position</legend>
							<label for="position">
								<select name="position" id="position">
									<option value="bottom" <?php echo $settings['position']=="bottom"? 'selected="selected"': '' ; ?>>Bottom</option>
									<option value="top" <?php echo $settings['position']=="top"? 'selected="selected"': '' ; ?>>Top</option>
									<option value="both" <?php echo $settings['position']=="both"? 'selected="selected"': '' ; ?>>Top &amp; Bottom</option>
								</select>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Navigate Within Category</th>
					<td>
						<fieldset>
							<legend class="hidden">Navigate Within Category</legend>
							<label for="nav_within_cat"><input type="checkbox" name="nav_within_cat" value="1" <?php echo $settings['nav_within_cat']=="1"? 'checked="checked"': '' ; ?>  /></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Reverse Navigation</th>
					<td>
						<fieldset>
							<legend class="hidden">Reverse Navigation</legend>
							<label for="is_reversed"><input type="checkbox" name="is_reversed" value="1" <?php echo $settings['is_reversed']=="1"? 'checked="checked"': '' ; ?>  /> (Reverse Next/Previous Position) </label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">CSS Code for Links</th>
					<td>
						<fieldset>
							<legend class="hidden">CSS Code</legend>
							<label for="style_css"><textarea id="style_css" name="style_css" placeholder="text-decoration: none;" style="width: 400px;height: 140px;"><?php echo $settings['style']!=""? stripslashes($settings['style']): ''; ?></textarea></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Custom Text</th>
					<td>
						<fieldset>
							<legend class="hidden">Custom Text</legend>
							<label for="is_custom"><input type="checkbox" name="is_custom" value="1" <?php echo $settings['is_custom']=="1"? 'checked="checked"': '' ; ?>  /></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Previous Post Text</th>
					<td>
						<fieldset>
							<label for="custom_pre"><input type="text" name="custom_pre"  value="<?php echo $settings['custom_pre']!=""? stripslashes($settings['custom_pre']): ''; ?>"  /></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Next Post Text</th>
					<td>
						<fieldset>
							<label for="custom_next"><input type="text" name="custom_next"  value="<?php echo $settings['custom_next']!=""? stripslashes($settings['custom_next']): ''; ?>"  /></label>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row" style="width: 100px;">Use Images</th>
					<td>
						<fieldset>
							<legend class="hidden">Use Images</legend>
							<label for="navi_img"><input type="checkbox" name="navi_img" value="1" <?php echo $settings['navi_img']=="1"? 'checked="checked"': '' ; ?>  /></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Previous Post Image</th>
					<td>
						<fieldset>
							<legend class="hidden">Previous Post Image</legend>
							<label for="pre_img_link"><input type="text" name="pre_img_link"  value="<?php echo $settings['pre_img_link']!=""? stripslashes($settings['pre_img_link']): ''; ?>"  /></label>
							<br /><span style="color: #999;">i.e. http://www.ApnaGoogle.com/previous.png</span>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Next Post Image</th>
					<td>
						<fieldset>
							<legend class="hidden">Next Post Image</legend>
							<label for="next_img_link"><input type="text" name="next_img_link"  value="<?php echo $settings['next_img_link']!=""? stripslashes($settings['next_img_link']): ''; ?>"  /></label>
							<br /><span style="color: #999;">i.e. http://www.ApnaGoogle.com/next.png</span>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
	</div><!-- End Block -->

	
	
	<div class="submit" style="float: left; display: block; width: 100%;"><input type="submit" name="Submit" value=" Update " style="min-width: 100px;min-height: 30px;font-size: 14px;" /></div>
		</form>
	<div class="submit" style="float: left; display: block; width: 100%;">
		
		
	</div>
</div>
<div style="float:right;">
<!-- Start Plugin Information -->
	<div class="wp-social-box" style="width: 200px;">
	<h3>Plugin Information</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<td>
						This Plugin is Developed By <a href="http://sharp-coders.com" target="_blank" class="company">Sharp Coders</a>.<br />
						Visit <a href="http://sharp-coders.com/category/plugins/wp-plugins/" target="_blank">sharp-coders.com</a> for more plugins.<br />
						<strong>Author: </strong> <a href="https://plus.google.com/104763153154719100069" target="_blank">Anas Mir</a>
						<br />
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br /><br />
						<strong>Support: </strong> <a href="http://sharp-coders.com/plugins/wp-plugins/wp-post-navigation" target="_blank">Open Support Page</a>
						<br /><br />
						<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fsharpcoders&amp;send=false&amp;layout=standard&amp;width=180&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:180px; height:35px;" allowTransparency="true"></iframe>
						<br />
						<strong>Twitter: </strong>&nbsp;&nbsp;<a href="http://twitter.com/sharpcoderz" target="_blank">@SharpCoderz</a>
						
						<hr style="max-width: 180px;" />
						<strong>Other Plugins</strong><br />
						<a href="http://sharp-coders.com/plugins/wp-plugins/wp-social-share" target="_blank">WP Social Share</a>
						<br />
						<a href="http://sharp-coders.com/plugins/wp-plugins/wp-subscriber_form" target="_blank">WP Subscriber Form</a>
						<br />
						<a href="http://sharp-coders.com/plugins/wp-plugins/wp-ads-within-contents-wordpress-plugin" target="_blank">WP Ads Within Contents</a>
						<hr style="max-width: 180px;" />
						<strong>Friends</strong><br />
						<a href="http://scriptclerks.com" target="_blank">http://scriptclerks.com</a>
						<br />
						<a href="https://www.seoclerks.com/PHP/184793/SEOClerks-Affiliate-Store-Responsive" target="_blank">Auto Pilot Affiliate Store</a>
						<br /><br />
					</td>
				</tr>
				
			</tbody>
		</table>
	</div>
	<!-- End Information -->
</div>
</div>

