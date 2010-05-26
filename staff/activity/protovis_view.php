<?php

class activityView {

    var $model;

    function __construct( $model ) {
        $this->model = $model;
    }

    public function generateVisualisation() {
        
       $staffhits = $this->model->staffhitsresult;
       $staffposts = $this->model->staffpostresult;

         $text =  <<<EOF
<script type="text/javascript" src="/protovis/protovis-r3.1.js"></script>

<script type="text/javascript+protovis">
// Create the root panel and set the visualization's size to 150x150
var vis = new pv.Panel()
    .width(150)
    .height(150);

// Add the horizontal rules (grid lines), we add them first so they go in the back.
vis.add(pv.Rule)
    .data(pv.range(0, 3, .5))
    .bottom(function(d) d * 80 + 1)
  .add(pv.Label);

// Add the bars with the height corresponding to the values in the data property
vis.add(pv.Bar)
    .data([2, 1.2, 1.7, 1.5, .7])
    .width(20)
    .height(function(d) 80 * d)
    .bottom(0)
    .left(function() this.index * 25 + 25) // this.index is the position of the datum in the array
  .anchor("bottom").add(pv.Label); // Add a label to the bottom of each bar

// Render everything.
vis.render();
</script>
EOF;

       return $text;

    }

}




?>
