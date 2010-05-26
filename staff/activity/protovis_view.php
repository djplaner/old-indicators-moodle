<?php

class activityView {

    var $model;

    function __construct( $model ) {
        $this->model = $model;
    }

    public function generateVisualisation() {
        
       $staffhits = $this->model->staffhitsresult;
       $staffposts = $this->model->staffpostresult;

         $text = "<br />Staff Course Activity";
         $text .= "<br /><img src=\"http://chart.apis.google.com/chart?chs=170x70&chd=t:$staffhits&cht=gom&chf=bg,s,EFEFEF&chxt=x,y&chxl=0:||1:|Low||High\"></img>";

         //The forums graph
         $text .= "<br>Staff Forum Participation";
         $text .= "<br><img src=\"http://chart.apis.google.com/chart?chs=170x70&chd=t:$staffposts&cht=gom&chf=bg,s,EFEFEF&chxt=x,y&chxl=0:||1:|Low||High\"></img>";
//print "$text";
         return $text;

    }

}




?>
