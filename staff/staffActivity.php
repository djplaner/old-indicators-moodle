<?php

class staffActivity extends Indicator {

    public function generateText() {
        
        global $CFG, $COURSE, $USER;

        $SQL="SELECT (count(*)/count(distinct(l.userid)))
              FROM {$CFG->prefix}log l, {$CFG->prefix}course c, 
                   {$CFG->prefix}context cx, {$CFG->prefix}role_assignments ra, 
                   {$CFG->prefix}role r
              WHERE l.course = c.id and c.id = cx.instanceid and 
                    cx.id = ra.contextid and ra.roleid = r.id and 
                    l.userid = ra.userid and cx.contextlevel = '50' and 
                    c.idnumber like '%2010' and ra.roleid != '5'";

        $staffaverage=round(count_records_sql($SQL));

        //get the hits for this user in this term
        $SQL="SELECT COUNT(*) FROM {$CFG->prefix}LOG 
                WHERE COURSE='$COURSE->id' and userid='$USER->id'";
        $staffresult=count_records_sql($SQL);

        //get the average forum posts and replies for ALL staff this term
        $SQL="select (count(*)/count(distinct(userid))) 
              from {$CFG->prefix}log where course='$COURSE->id' and 
              userid='$USER->id' and 
              action in ('add discussion','add post','update post') and 
              course in 
              ( select id from {$CFG->prefix}course 
                  where idnumber like '%2010') and userid in
                  ( select userid from {$CFG->prefix}role_assignments 
                    where roleid !='5' and contextid in
                    ( select id from {$CFG->prefix}context where 
                      contextlevel='50'))";
         $allstaffaverage=count_records_sql($SQL);

         //get the number of posts and replies for this user
         $SQL="select count(*) from {$CFG->prefix}log 
                where course='$COURSE->id' and userid='$USER->id' and 
                action in ('add discussion','add post','update post')";
         $staffposts=count_records_sql($SQL);

         ////////////Produce the graphs
         //The Hits graph
         $text = "";

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
/*         $text = "<br />Staff Course Activity";
         $staffaverage=1;  $allstaffaverage=1;
         $muliplier=(100/(2*$staffaverage));
         $staffhitsresult=round($staffresult*$muliplier);
         $text .= "<br /><img src=\"http://chart.apis.google.com/chart?chs=170x70&chd=t:$staffhitsresult&cht=gom&chf=bg,s,EFEFEF&chxt=x,y&chxl=0:||1:|Low||High\"></img>";

         //The forums graph
         $text .= "<br>Staff Forum Participation";
         $muliplier=(100/(2*$allstaffaverage));
         $staffpostresult=round($staffposts*$muliplier);
         $text .= "<br><img src=\"http://chart.apis.google.com/chart?chs=170x70&chd=t:$staffpostresult&cht=gom&chf=bg,s,EFEFEF&chxt=x,y&chxl=0:||1:|Low||High\"></img>";
*/
         return $text;

    }

}




?>
