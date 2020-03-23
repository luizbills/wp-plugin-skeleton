<?php

namespace src_namespace__\functions;

function notify ( $message, $options = [] ) {
	if ( \apply_filters( prefix( 'notify_email_enabled' ), true ) ) {
		$recipients = array_get( $options, 'email_recipients', [] );
		$subject = array_get( $options, 'email_subject', config_get( 'NAME' ) . ' Notification' );
		$headers = [
			array_get( $options, 'email_content_type', 'content-type: text/html; charset=utf-8' )
		];

		// notify site admin by default
		if ( array_get( $options, 'email_to_admin', true ) ) {
			$recipients[] = \get_bloginfo( 'admin_email' );
		}

		// send email
		\wp_mail(
			\apply_filters( prefix( 'notify_email_recipients' ), $recipients, $options ),
			\apply_filters( prefix( 'notify_email_subject' ), $subject, $options ),
			\apply_filters( prefix( 'notify_email_message' ), $message, $options ),
			\apply_filters( prefix( 'notify_email_headers' ), $headers, $options )
		);
	}

	\do_action( prefix( 'notify' ), $options );
	logf( "Notification sent: {$message}" );
}
