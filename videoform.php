<!-- Video Metabox for Custom Post Types -->
<div id="goroutwp_video_form" class="gorout_form">
    <p><label><strong>Video Type:</strong> <em>Vimeo is the default, but YouTube is OK.</em></label> </p>
    <select id="video_type" name="video_type" class="input width-90 required">
        <option value="vimeo" <?php if($video_type == "vimeo"): ?>selected="selected"<?php endif; ?>>Vimeo</option>
        <option value="youtube" <?php if($video_type == "youtube"): ?>selected="selected"<?php endif; ?>>YouTube</option>
    </select> 
    <p><label><strong>Video ID:</strong> <em>You only need the video code (not full URL).</em></label></p>
    <input id="video_code" name="video_code" class="input width-90 required" type="text" value="<?php echo esc_attr( $video_code ); ?>" placeholder="Video ID" required /> <br />
    <br />
</div>