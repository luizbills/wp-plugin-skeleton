<?php /** read more about `v` function in https://github.com/luizbills/v */ ?>
<div class="notice <?= v( $var['class'] ) ?>">
	<p><?= v( $var['message'], 'raw', 'safe_html' ) ?></p>
</div>
