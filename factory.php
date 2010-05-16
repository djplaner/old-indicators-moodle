<?php
/* FILE:     factory.php
 * PURPOSE:  Define the abstract indicator and IndicatorFactory class
 *
 */
 
abstract class Indicator {
    
    public function generateText() {
    }
}

class IndicatorFactory {
    
    public static function create( $context ) {

        global $CFG;
        
        if ( has_capability( 'moodle/legacy:teacher', $context ) ||
             has_capability( 'moodle/legacy:editingteacher', $context ) ) {
            require_once( 
                    $CFG->dirroot.'/blocks/indicators/staff/staffActivity.php');
            return new staffActivity;
        } else if ( has_capability( 'moodle/legacy:student', $context ) ) {
            require_once( 
                    $CFG->dirroot.'/blocks/indicators/student/studentActivity.php');
            return new studentActivity;
        } 
    }
}

?>
