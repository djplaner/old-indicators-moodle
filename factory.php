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
    
    public static function createModel( $context ) {

        global $CFG;
        
        if ( has_capability( 'moodle/legacy:teacher', $context ) ||
             has_capability( 'moodle/legacy:editingteacher', $context ) ) {
            require_once( 
                    $CFG->dirroot.'/blocks/indicators/staff/activity/model.php');
            return new activityModel;
        } else if ( has_capability( 'moodle/legacy:student', $context ) ) {
            require_once( 
                    $CFG->dirroot.'/blocks/indicators/student/activity/model.php');
            return new activityModel;
        } 
    }

    # - create the view object
    # - currently use Protovis for students and Google for staff

    public static function createView( $model, $context ) {

        global $CFG;

        if ( has_capability( 'moodle/legacy:teacher', $context ) ||
             has_capability( 'moodle/legacy:editingteacher', $context ) ) {
            $view = "/blocks/indicators/staff/activity/google_view.php";
            if ( rand( 0, 1 ) == 1 ) {
                $view = "/blocks/indicators/staff/activity/protovis_view.php";
            } 
            require_once( $CFG->dirroot.$view );
            return new activityView( $model );
        } else if ( has_capability( 'moodle/legacy:student', $context ) ) {
            require_once(                    
                $CFG->dirroot.'/blocks/indicators/student/activity/protovis_view.php');
            return new activityView( $model );
        }
    }

}

?>
