<?php

namespace src_namespace__\functions;

function notify ( $message, $options = [] ) {
	// default handler
	if ( isset( $options['email'] ) ) {
		$opts = array_get( $options, 'email', [] );

		$recipients    = array_get( $opts, 'recipients', [] );
		$subject       = array_get( $opts, 'subject', config_get( 'NAME' ) . ' Notification' );
		$content_type  = array_get( $opts, 'content_type', 'content-type: text/html; charset=utf-8' );

		// notify site admin by default
		if ( array_get( $opts, 'to_admin', true ) ) {
			$recipients[] = \get_bloginfo( 'admin_email' );
		}

		if ( isset( $opts['handler'] ) && is_callable( $opts['handler'] ) ) {
			call_user_func( $opts['handler'], $message, $opts );
		} else {
			\wp_mail(
				\apply_filters( prefix( 'notify_email_recipients' ), $recipients, $opts ),
				\apply_filters( prefix( 'notify_email_subject' ), $subject, $opts ),
				\apply_filters( prefix( 'notify_email_message' ), $message, $opts ),
				\apply_filters( prefix( 'notify_email_headers' ), $headers, $opts )
			);
		}
	}

	unset( $options['email'] );

	foreach ( $options as $type => $opts ) {
		if ( isset( $opts['handler'] ) && is_callable( $opts['handler'] ) ) {
			call_user_func( $opts['handler'], $message, $opts );
		} else {
			\do_action( h\prefix( "handle_notification_$type" ), $message, $opts );
		}
	}

	logf( "Notification sent: {$message}" );
}
