<style>
.triberr_comment_system {
	margin: 10px 0 0 0;
	text-align: center;
	width:100%;
}
</style>
<?PHP

//global $post;
$triberr_id = get_post_meta( $post->ID, '_triberr_id', true );
$url = get_permalink( $post->ID );
$triberr_comments_bg_color = get_option( 'triberr_comments_bg_color' );
// Strip out the # in case it's in there
$triberr_comments_bg_color = str_replace( '#', '', $triberr_comments_bg_color );

$triberr_comments_status = get_option( 'triberr_comments_status' );
$triberr_comments_width = get_option( 'triberr_comments_width' );

		$triberr_comments = "
        <script type=\"text/javascript\">
  function resizeCommentsIframe(id, other_domain) {
    var iframe = document.getElementById(id);
    window.addEventListener('message', function(event) {
      if (event.origin !== other_domain) return; // only accept messages from the specified domain
      if (isNaN(event.data)) return; // only accept something which can be parsed as a number
      var height = parseInt(event.data) + 0; // add some extra height to avoid scrollbar
      iframe.height = height + \"px\";
    }, false);
  }
</script>
<div class=\"triberr_comment_system\" id=\"triberr_comment_system\" >
<iframe src='http://triberr.com/subdomains/plugins/comments/triberr_comment_loader.php?post_id=$triberr_id&bgcolor=$triberr_comments_bg_color&width=$triberr_comments_width&parent=$url' scrolling=\"no\" frameborder=\"0\" width=\"$triberr_comments_width\" id=\"triberr_comments_iframe\" onload=\"resizeCommentsIframe('triberr_comments_iframe', 'http://triberr.com');\">
</iframe>
</div>
        ";

if ( ( 'yes' === $triberr_comments_status || 'on' === $triberr_comments_status ) && '' !== $triberr_id ) {
	echo $triberr_comments;
} else {
	echo "<!-- triberr_comments_status: $triberr_comments_status and triberr_id: $triberr_id -->";
}
