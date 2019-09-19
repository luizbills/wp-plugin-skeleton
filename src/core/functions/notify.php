<?php

namespace src_namespace__\functions;

function notify ( $message, $options = [] ) {
	$options['message'] = $message;

	if ( \apply_filters( prefix( 'notify_email_enabled' ), true ) ) {
		$recipients = [ \get_bloginfo( 'admin_email' ) ];
		$subject = config_get( 'NAME' ) . ' Notification';
		$headers = [
			array_get( $options, 'email_content_type', 'content-type: text/html; charset=utf-8' )
		];

		\wp_mail(
			\apply_filters( prefix( 'notify_email_recipients' ), $recipients, $options ),
			\apply_filters( prefix( 'notify_email_subject' ), $subject, $options ),
			\apply_filters( prefix( 'notify_email_message' ), $options['message'], $options ),
			\apply_filters( prefix( 'notify_email_headers' ), $headers, $options )
		);
	}

	\do_action( prefix( 'notify' ), $options );
	log( "Notification sent: ${options['message']}" );
}
