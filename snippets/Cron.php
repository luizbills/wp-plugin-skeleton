<?php

namespace {ns}\Cron;

use {ns}\functions as h;
use {ns}\Common\Hooker_Trait;

final class Schedule_Event {
	use Hooker_Trait;

	const ACTION = '__CHANGE_ME__';

	public static function __deactivation () {
		$schedule_action = h\prefix( self::ACTION );
		$timestamp = wp_next_scheduled( $schedule_action );
		\wp_unschedule_event( $timestamp, $schedule_action );
	}

	public function __boot () {
		// $this->add_filter( 'cron_schedules', 'add_cron_interval' );
		
    $schedule_action = h\prefix( self::ACTION );
		if ( ! \wp_next_scheduled( $schedule_action ) ) {
			\wp_schedule_event( time(), 'hourly', $schedule_action );
		}
	}

	public function __init () {
		$schedule_action = h\prefix( self::ACTION );
		$this->add_action( $schedule_action, 'callback' );
	}

	public function callback () {
		// TODO
	}
}
