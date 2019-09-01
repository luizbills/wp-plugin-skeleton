<?php /** read more about `v` function in core/functions/templater.php */ ?>
<div class="notice <?= v( $class, 'esc_attr' ) ?>">
	<p><?= v( $message, 'raw' ) ?></p>
</div>
