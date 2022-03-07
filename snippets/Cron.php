<?php

namespace {ns};

use {ns}\functions as h;
use {ns}\Common\Hooker_Trait;

final class Cron {
	use Hooker_Trait;

	const ACTION = '__CHANGE_ME__';

	public static function __deactivation () {
		$schedule_action = h\prefix( self::ACTION );
		$timestamp = \wp_next_scheduled( $schedule_action );
		\wp_unschedule_event( $timestamp, $schedule_action );
	}

	public function __boot () {
		// $this->add_filter( 'cron_schedules', 'add_cron_interval' );
		
		$schedule_action = h\prefix( self::ACTION );
		if ( ! \wp_next_scheduled( $schedule_action ) ) {
			\wp_schedule_event( time(), $this->get_recurrence(), $schedule_action );
		}
	}

	public function __init () {
		$schedule_action = h\prefix( self::ACTION );
		$this->add_action( $schedule_action, 'callback' );
	}
	
	public function get_recurrence () {
		return 'hourly';
	}
	
	// public function add_cron_interval ( $schedules ) {
	// 	$schedules[ 'every_1_minute' ] = [
	// 		'interval' => 1 * MINUTE_IN_SECONDS,
	// 		'display' => esc_html__( 'Every 1 minute' )
	// 	];
	// 	return $schedules;
	// }

	public function callback () {
		// TODO
	}
}
