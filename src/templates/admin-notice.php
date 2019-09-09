<?php
use src_namespace__\functions as h;
h\array_ensure_keys( $var, [ 'class', 'message' ] ); 
?>
<div class="notice <?= v( $var['class'] ) ?>">
	<p><?= v( $var['message'], 'default("Missing Message")', 'safe_html', 'raw' ) ?></p>
</div>
