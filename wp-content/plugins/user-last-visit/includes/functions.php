<?php
/**
 * Get user last visit
 *
 * @param   mixed   $user_id, user ID or current to use the current logged in user
 * @param   bool    $vebrose, whether to return a verbose response or just an integer/boolean
 * @return  mixed   $last_visit, a literal expression of the last visit time, or a timestamp. FALSE if no record or invalid user.
 *
 * @since   0.8
 */
function user_last_visit( $user_id = 'current', $verbose = true ) {
    $last_visit = User_Last_Visit::get_user_last_visit( $user_id );
    if ( $last_visit ) {
        if ( $verbose ) {
            $now = date_create( 'now' );
            $record = date_create( '@' . $last_visit );
            $diff = $record->diff($now);
            return ulv_time_diff_verbose( $diff );
        }
    }
    return $last_visit;
}

/**
 * Literal wording of past UNIX timestamp
 *
 * @param   DateInterval $time_diff, date interval between the last visit and current time
 * @return  str, the literal expression of the date interval
 *
 * @since   0.8
 */
function ulv_time_diff_verbose( $time_diff ) {
    $int_diff = array(
        array(
            'value' => $time_diff->y,
            'inc' => 100,
            'singular' => __( 'year', 'ulv' ),
            'plural' => __( 'years', 'ulv' ),
        ),
        array(
            'value' => $time_diff->m,
            'inc' => 9,
            'singular' => __( 'month', 'ulv' ),
            'plural' => __( 'months', 'ulv' ),
        ),
        array(
            'value' => $time_diff->d,
            'inc' => 22,
            'singular' => __( 'day', 'ulv' ),
            'plural' => __( 'days', 'ulv' ),
        ),
        array(
            'value' => $time_diff->h,
            'inc' => 18,
            'singular' => __( 'hour', 'ulv' ),
            'plural' => __( 'hours', 'ulv' ),
        ),
        array(
            'value' => $time_diff->i,
            'inc' => 45,
            'singular' => __( 'minute', 'ulv' ),
            'plural' => __( 'minutes', 'ulv' ),
        ),
        array(
            'value' => $time_diff->s,
            'inc' => null,
            'singular' => __( 'second', 'ulv' ),
            'plural' => __( 'seconds', 'ulv' ),
        ),
    );
    $interval = '';
    foreach ( $int_diff as $key => $elem ) {
        if ( 0 == $elem['value'] && ( ! isset( $int_diff[ $key + 1 ] ) || empty( $int_diff[ $key + 1 ]['inc'] ) || ( $int_diff[ $key + 1 ]['value'] <= $int_diff[ $key + 1 ]['inc'] ) ) ) {
            continue;
        }
        if ( isset( $int_diff[ $key + 1 ] ) && ! empty( $int_diff[ $key + 1 ]['inc'] ) && ( $int_diff[ $key + 1 ]['value'] > $int_diff[ $key + 1 ]['inc'] ) ) {
            $interval .= ( $elem['value'] + 1 ) . ' ' . ( ( 1 == ( $elem['value'] + 1 ) )? $elem['singular'] : $elem['plural'] );
            break;
        } else {
            $interval .= $elem['value'] . ' ' . ( ( 1 == $elem['value'] )? $elem['singular'] : $elem['plural'] );
            if ( isset( $int_diff[ $key + 1 ] ) && 0 != $int_diff[ $key + 1 ]['value'] ) {
                $interval .= ' ' . $int_diff[ $key + 1 ]['value'] . ' ' . ( ( 1 == $int_diff[ $key + 1 ]['value'] )? $int_diff[ $key + 1 ]['singular'] : $int_diff[ $key + 1 ]['plural'] );
            }
            break;
        }
    }
    
    return sprintf( __( '%s ago', 'ulv' ), $interval );
}

// Plugin's description in "plugins.php" page
__( 'Keep record of user last visit time based on user id and login status' );
