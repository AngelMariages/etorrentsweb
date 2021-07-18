<?php

class WPZOOM_Importer_Logger extends WP_Importer_Logger {
	public $min_level = 'notice';

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log( $level, $message, array $context = array() ) {
		if (!WP_DEBUG && !in_array($this->level_to_numeric( $level ), array( 2, 3 ))  ) {
			return;
		}

		printf(
			'<span class="%s">[%s]</span> %s' . PHP_EOL,
			$level,
			strtoupper( $level ),
			$message
		);
	}

	public static function level_to_numeric( $level ) {
		$levels = array(
			'emergency' => 8,
			'alert'     => 7,
			'critical'  => 6,
			'error'     => 5,
			'warning'   => 4,
			'notice'    => 3,
			'info'      => 2,
			'debug'     => 1,
		);
		if ( ! isset( $levels[ $level ] ) ) {
			return 0;
		}

		return $levels[ $level ];
	}
}