<?php

// get ics dynamic file for a event

if(isset($_GET['event_id']) && !empty($_GET['event_id']) ){
	$event_id =$_GET['event_id'];
}else{
	die();
}


require_once('../../../wp-blog-header.php');
//include_once('eventon.php');
global $eventon;	

$eventon->evo_generator->generate_ics_for_event($event_id);
//echo $eventon->get_current_version();

//$the_event = get_post($event_id);

//echo $event_id;

?>