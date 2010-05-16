<?php

class studentActivity extends Indicator {

    public function generateText() {
        
        global $CFG, $COURSE, $USER;

        $text = "Effort Tracker for $USER->username";

        /////Get this users hitcount
        $SQL="SELECT COUNT(*) FROM {$CFG->prefix}LOG WHERE COURSE='$COURSE->id' and userid='$USER->id'";
        $studentresult=count_records_sql($SQL);

        /////Get the average for all student users
        $SQL="SELECT (count(*)/count(distinct(userid))) FROM {$CFG->prefix}LOG WHERE COURSE='$COURSE->id' and userid in (select userid from {$CFG->prefix}role_assignments where contextid in
                (select id from {$CFG->prefix}context where contextlevel='50' and instanceid ='$COURSE->id')
                and roleid in  (select id from {$CFG->prefix}role where name='Student'))"; //***** Needs work tis crappy
        $avg=round(count_records_sql($SQL));
        $muliplier=(100/(2*$avg));
        $studentresult=round($studentresult*$muliplier);
        $text .= "<br><img src=\"http://chart.apis.google.com/chart?chs=170x70&chd=t:$studentresult&cht=gom&chf=bg,s,EFEFEF&chxt=x,y&chxl=0:||1:|Low||High\"></img>";

        return $text;
    }

}




?>
