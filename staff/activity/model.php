<?php

class activityModel extends Indicator {

    var $staffhitsresult, $staffpostresult;
    function __construct() {
        
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

         $staffaverage=1;  $allstaffaverage=1;
         $muliplier=(100/(2*$staffaverage));
         $this->staffhitsresult=round($staffresult*$muliplier);

         //The forums graph
         $muliplier=(100/(2*$allstaffaverage));
         $this->staffpostresult=round($staffposts*$muliplier);
    }

}




?>
