<br />
<form name="nuffimage_form" action="{U_NUFFIMAGE_ACTION}" method="post">
<table class="forumline">
	<tr><th class="tdnw" colspan="7">{L_NUFF_TITLE}</th></tr>
	<tr><td class="row3" colspan="7"><span class="gen">{L_NUFF_EXPLAIN}</span></td></tr>
	<tr>
		<td class="row1 row-center tw2pct"><img src="{IMG_NORMAL}" alt="" /></td>
		<td class="row2 row-center tw2pct">&nbsp;</td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_NORMAL}</b><br /><br />{L_NUFF_NORMAL_EXPLAIN}</span></td>
		<td class="row2 row-center tw2pct">&nbsp;</td>
		<td class="row1 row-center tw2pct"><img src="{IMG_RESIZE}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_resize" value="1" {NUFF_RESIZE_CHECKED} /></td>
		<td class="row1 tw45pct">
			<span class="gen"><b>{L_NUFF_RESIZE}</b><br /><br />
				{L_NUFF_RESIZE_W}&nbsp;
				<select name="nuff_resize_w">
					<option value="0" selected="selected">{L_NUFF_RESIZE_NO_RESIZE}</option>
					<option value="100">100</option>
					<option value="200">200</option>
					<option value="300">300</option>
					<option value="400">400</option>
					<option value="640">640</option>
					<option value="800">800</option>
					<option value="1024">1024</option>
				</select><br />
				{L_NUFF_RESIZE_H}&nbsp;
				<select name="nuff_resize_h">
					<option value="0" selected="selected">{L_NUFF_RESIZE_NO_RESIZE}</option>
					<option value="75">75</option>
					<option value="150">150</option>
					<option value="225">225</option>
					<option value="300">300</option>
					<option value="480">480</option>
					<option value="600">600</option>
					<option value="768">768</option>
				</select>
			</span>
		</td>
	</tr>
	<tr>
		<td class="row1 row-center tw2pct"><img src="{IMG_ROTATE}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_rotation" value="1" {NUFF_ROTATE_CHECKED} /></td>
		<td class="row1 tw45pct">
			<span class="gen"><b>{L_NUFF_ROTATE}</b><br /><br /></span>
			<input type="radio" name="nuff_rotation_d" value="0" checked="checked" /><span class="gen">0</span>&nbsp;&nbsp;<input type="radio" name="nuff_rotation_d" value="90" /><span class="gen">90</span>&nbsp;&nbsp;<input type="radio" name="nuff_rotation_d" value="180" /><span class="gen">180</span>&nbsp;&nbsp;<input type="radio" name="nuff_rotation_d" value="270" /><span class="gen">270</span>
		</td>
		<td class="row2 row-center tw2pct">&nbsp;</td>
		<td class="row1 row-center tw2pct"><img src="{IMG_RECOMPRESS}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_recompress" value="1" {NUFF_RECOMPRESS_CHECKED} /></td>
		<td class="row1 tw45pct">
			<span class="gen"><b>{L_NUFF_RECOMPRESS}</b><br /><br />
				{L_NUFF_RECOMPRESS}&nbsp;
				<select name="nuff_recompress_r">
					<option value="0" selected="selected">{L_NUFF_RESIZE_NO_RESIZE}</option>
					<option value="90">90</option>
					<option value="80">80</option>
					<option value="70">70</option>
					<option value="50">50</option>
					<option value="25">25</option>
				</select>
			</span>
		</td>
	</tr>
	<tr>
		<td class="row1 row-center tw2pct"><img src="{IMG_MIRROR}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_mirror" value="1" {NUFF_MIRROR_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_MIRROR}</b><br /><br />{L_NUFF_MIRROR_EXPLAIN}</span></td>
		<td class="row2 row-center tw2pct">&nbsp;</td>
		<td class="row1 row-center tw2pct"><img src="{IMG_FLIP}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_flip" value="1" {NUFF_FLIP_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_FLIP}</b><br /><br />{L_NUFF_FLIP_EXPLAIN}</span></td>
	</tr>
	<tr>
		<td class="row1 row-center tw2pct"><img src="{IMG_INTERLACE}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_interlace" value="1" {NUFF_INTERLACE_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_INTERLACE}</b><br /><br />{L_NUFF_INTERLACE_EXPLAIN}</span></td>
		<td class="row2 row-center tw2pct">&nbsp;</td>
		<td class="row1 row-center tw2pct"><img src="{IMG_SCREEN}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_screen" value="1" {NUFF_SCREEN_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_SCREEN}</b><br /><br />{L_NUFF_SCREEN_EXPLAIN}</span></td>
	</tr>
	<!-- BEGIN sepia_bw_enabled -->
	<tr>
		<td class="row1 row-center tw2pct"><img src="{IMG_BW}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_bw" value="1" {NUFF_BW_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_BW}</b><br /><br />{L_NUFF_BW_EXPLAIN}</span></td>
		<td class="row2 row-center tw2pct">&nbsp;</td>
		<td class="row1 row-center tw2pct"><img src="{IMG_SEPIA}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_sepia" value="1" {NUFF_SEPIA_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_SEPIA}</b><br /><br />{L_NUFF_SEPIA_EXPLAIN}</span></td>
	</tr>
	<tr>
		<td class="row1 row-center tw2pct"><img src="{IMG_BLUR}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_blur" value="1" {NUFF_BLUR_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_BLUR}</b><br /><br />{L_NUFF_BLUR_EXPLAIN}</span></td>
		<td class="row2 row-center tw2pct">&nbsp;</td>
		<td class="row1 row-center tw2pct"><img src="{IMG_SCATTER}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_scatter" value="1" {NUFF_SCATTER_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_SCATTER}</b><br /><br />{L_NUFF_SCATTER_EXPLAIN}</span></td>
	</tr>
	<tr>
		<td class="row1 row-center tw2pct"><img src="{IMG_PIXELATE}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_pixelate" value="1" {NUFF_PIXELATE_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_PIXELATE}</b><br /><br />{L_NUFF_PIXELATE_EXPLAIN}</span></td>
		<td class="row2 row-center tw2pct">&nbsp;</td>
		<td class="row1 row-center tw2pct"><img src="{IMG_STEREOGRAM}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_stereogram" value="1" {NUFF_STEREOGRAM_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_STEREOGRAM}</b><br /><br />{L_NUFF_STEREOGRAM_EXPLAIN}</span></td>
	</tr>
	<tr>
		<td class="row1 row-center tw2pct"><img src="{IMG_INFRARED}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_infrared" value="1" {NUFF_INFRARED_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_INFRARED}</b><br /><br />{L_NUFF_INFRARED_EXPLAIN}</span></td>
		<td class="row2 row-center tw2pct">&nbsp;</td>
		<td class="row1 row-center tw2pct"><img src="{IMG_TINT}" alt="" /></td>
		<td class="row2 row-center tw2pct"><input type="checkbox" name="nuff_tint" value="1" {NUFF_TINT_CHECKED} /></td>
		<td class="row1 tw45pct"><span class="gen"><b>{L_NUFF_TINT}</b><br /><br />{L_NUFF_TINT_EXPLAIN}</span></td>
	</tr>
	<!-- END sepia_bw_enabled -->
	<tr><td class="row3 row-center" colspan="7"><span class="gen"><input type="submit" value="{L_SUBMIT}" class="mainoption" /></span></td></tr>
</table>
</form>
<br />