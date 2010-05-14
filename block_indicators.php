<?php //$Id: block_indicators.php,v0.1 2010/05/11 ColinBeer

class block_indicators extends block_base {
    function init() {
        $this->title = get_string("indicators","block_indicators");
        $this->version = 2010051010;
    }

    function get_content() {
        global $USER, $CFG, $COURSE, $SESSION;
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
        $context = get_context_instance(CONTEXT_COURSE,$SESSION->cal_course_referer);
        $canview=-1;

        if ( has_capability( 'moodle/legacy:teacher', $context )) {
            $canview=1;
        } else if ( has_capability( 'moodle/legacy:student', $context )) {
            print "This is a student<br />";
            $canview=0;
        }
        if($canview == 1)
        {
        ///// THE STAFF SECTION
          $this->content->text .= "";
          $this->content->text .= "";
          
          //get the average hits for ALL the staff in this term. SQL needs some thought as course id numbers and shortnames will change between institutions
          $SQL="SELECT (count(*)/count(distinct(l.userid)))
                FROM {$CFG->prefix}log l, {$CFG->prefix}course c, {$CFG->prefix}context cx, {$CFG->prefix}role_assignments ra, {$CFG->prefix}role r
                WHERE l.course = c.id
                and c.id = cx.instanceid
                and cx.id = ra.contextid
                and ra.roleid = r.id
                and l.userid = ra.userid
                and cx.contextlevel = '50'
                and c.idnumber like '%2010'
                and ra.roleid != '5'";
          $staffaverage=round(count_records_sql($SQL));
          
          //get the hits for this user in this term
          $SQL="SELECT COUNT(*) FROM {$CFG->prefix}LOG WHERE COURSE='$COURSE->id' and userid='$USER->id'";
          $staffresult=count_records_sql($SQL);
          
          //get the average forum posts and replies for ALL staff this term
          $SQL="select (count(*)/count(distinct(userid))) from {$CFG->prefix}log where course='$COURSE->id' and userid='$USER->id' and action in ('add discussion','add post','update post')
                  and course in 
                  (select id from m_course where idnumber like '%2010')
                  and userid in
                  ( select userid from m_role_assignments where roleid !='5' and contextid in
                  (select id from m_context where contextlevel='50'))";
          $allstaffaverage=count_records_sql($SQL);
          
          //get the number of posts and replies for this user
          $SQL="select count(*) from {$CFG->prefix}log where course='$COURSE->id' and userid='$USER->id' and action in ('add discussion','add post','update post')";
          $staffposts=count_records_sql($SQL);
          
          ////////////Produce the graphs
          //The Hits graph
          $this->content->text .= "<br>Staff Course Activity";
          $muliplier=(100/(2*$staffaverage));
          $staffhitsresult=round($staffresult*$muliplier);
          $this->content->text .= "<br><img src=\"http://chart.apis.google.com/chart?chs=170x70&chd=t:$staffhitsresult&cht=gom&chf=bg,s,EFEFEF&chxt=x,y&chxl=0:||1:|Low||High\"</img>";
          
          //The forums graph
          $this->content->text .= "<br>Staff Forum Participation";
          $muliplier=(100/(2*$allstaffaverage));
          $staffpostresult=round($staffposts*$muliplier);
          $this->content->text .= "<br><img src=\"http://chart.apis.google.com/chart?chs=170x70&chd=t:$staffpostresult&cht=gom&chf=bg,s,EFEFEF&chxt=x,y&chxl=0:||1:|Low||High\"</img>";

          
        } else
        {
        ///// THE STUDENT SECTION /////
          $this->content->text .= "Effort Tracker for $USER->username";
          
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
          $this->content->text .= "<br><img src=\"http://chart.apis.google.com/chart?chs=170x70&chd=t:$studentresult&cht=gom&chf=bg,s,EFEFEF&chxt=x,y&chxl=0:||1:|Low||High\"</img>";        
        }      
    }
}

?>
