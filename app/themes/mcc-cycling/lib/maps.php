<?php
/**
 * maps.php
 * Author: Tom Seldon
 * Created: 07/02/2014
 */

function mcc_get_route_markers()
{
    $markers = array();

    $markers['abergavenny'] = array(
        'title'     => 'Abergavenny',
        'lat'       =>  '51.825366',
        'lng'       =>  '-3.019423'
    );

    $markers['chepstow'] = array(
        'title'     => 'Chepstow',
        'lat'       =>  '51.641856',
        'lng'       =>  '-2.673804'
    );

    $markers['monmouth'] = array(
        'title'     => 'Monmouth',
        'lat'       =>  '51.816132',
        'lng'       =>  '-2.714501'
    );

    $markers['celtic_manor'] = array(
        'title'     => 'The Celtic Manor Resort, Newport',
        'lat'       =>  '51.602206',
        'lng'       =>  '-2.931387'
    );

    return $markers;
}