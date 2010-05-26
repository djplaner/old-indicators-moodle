<?php //$Id: block_indicators.php,v0.1 2010/05/11 ColinBeer

require_once( $CFG->dirroot.'/blocks/indicators/factory.php' );

class block_indicators extends block_base {
    function init() {
        $this->title = get_string("indicators","block_indicators");
        $this->version = 2010051010;
    }

    function get_content() {
        global $SESSION;
        if ($this->content !== NULL) {
            return $this->content;
        }
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';
        if (empty($this->instance)) {
            return $this->content;
        }

        /////Check the user level and separate students and staff
        $context = get_context_instance(CONTEXT_COURSE,
                                $SESSION->cal_course_referer);

        $model = IndicatorFactory::createModel( $context );
        $view = IndicatorFactory::createView( $model, $context );
        $this->content->text = $view->generateVisualisation();
        return $this->content;
    }
}

?>
