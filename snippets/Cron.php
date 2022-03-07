<?php

namespace {ns};

use {ns}\functions as h;
use {ns}\Common\Hooker_Trait;

final class Cron {
	use Hooker_Trait;

	public static function get_action () {
		return h\prefix( 'cron' ); 
	}

	public static function __deactivation () {
		$cron_action = h\prefix( self::get_action() );
		$timestamp = \wp_next_scheduled( $cron_action );
		\wp_unschedule_event( $timestamp, $cron_action );
	}

	public function __boot () {
		// $this->add_filter( 'cron_schedules', 'add_cron_interval' );
		
		$cron_action = h\prefix( self::get_action() );
		if ( ! \wp_next_scheduled( $cron_action ) ) {
			\wp_schedule_event( time(), $this->get_recurrence(), $cron_action );
		}
	}

	public function __init () {
		$cron_action = h\prefix( self::get_action() );
		$this->add_action( $cron_action, 'callback' );
	}
	
	public function callback () {
		// TODO
	}

	public function get_recurrence () {
		return 'hourly';
	}

	// public function add_cron_interval ( $schedules ) {
	// 	$schedules[ 'every_minute' ] = [
	// 		'interval' => 1 * \MINUTE_IN_SECONDS,
	// 		'display' => \__( 'Every minute' )
	// 	];
	// 	return $schedules;
	// }
}
