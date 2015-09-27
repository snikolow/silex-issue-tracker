<?php

namespace Tracker\Helper\DateTime;

class TimeAgo {

    /**
     *
     * @param int $timestamp
     * @return string
     */
    public static function formatDate($timestamp) {
        $currentTimestamp = (new \DateTime())->getTimestamp();
        $estimateTime = $currentTimestamp - $timestamp;

        if( $estimateTime < 1 ) {
            return 'less than 1 second ago';
        }

        $map = array(
            12 * 30 * 24 * 60 * 60  => 'year',
            30 * 24 * 60 * 60       => 'month',
            24 * 60 * 60            => 'day',
            60 * 60                 => 'hour',
            60                      => 'minute',
            1                       => 'second'
        );

        foreach($map as $secs => $str) {
            $time = $estimateTime / $secs;

            if( $time >= 1 ) {
                $roundTime = round($time);
                return sprintf(
                        'about %s %s ago',
                        $roundTime,
                        $str . (($roundTime > 1) ? 's' : '')
                );
            }
        }
    }

}
