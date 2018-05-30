<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class EscalationTimes {
        var $EscalationTimeID;
        var $TimePeriod;

        function MakeSafe(){
                $this->EscalationTimeID=intval($this->EscalationTimeID);
                $this->TimePeriod=sanitize($this->TimePeriod);
        }

        function MakeDisplay(){
                $this->TimePeriod=stripslashes($this->TimePeriod);
        }

        function query($sql){
                global $dbh;
                return $dbh->query($sql);
        }

        function exec($sql){
                global $dbh;
                return $dbh->exec($sql);
        }

        function CreatePeriod(){
                global $dbh;
                $this->MakeSafe();

                $sql="INSERT INTO fac_EscalationTimes SET TimePeriod=\"$this->TimePeriod\";";

                if($this->exec($sql)){
                        $this->EscalationTimeID=$dbh->lastInsertId();
                        $this->MakeDisplay();
                        (class_exists('LogActions'))?LogActions::LogThis($this):'';
                        return $this->EscalationTimeID;
                }else{
                        return false;
                }
        }

        function DeletePeriod(){
                $this->MakeSafe();

                $sql="DELETE FROM fac_EscalationTimes WHERE EscalationTimeID=$this->EscalationTimeID;";

                (class_exists('LogActions'))?LogActions::LogThis($this):'';
                return $this->exec($sql);
        }

        function GetEscalationTime(){
                $sql="SELECT * FROM fac_EscalationTimes WHERE EscalationTimeID=$this->EscalationTimeID;";

                if($row=$this->query($sql)->fetch()){
                        $this->EscalationTimeID=$row["EscalationTimeID"];
                        $this->TimePeriod=$row["TimePeriod"];
                        $this->MakeDisplay();
                        return true;
                }else{
                        return false;
                }
        }

        function GetEscalationTimeList(){
                $sql="SELECT * FROM fac_EscalationTimes ORDER BY TimePeriod ASC;";

                $escList=array();
                foreach($this->query($sql) as $row){
                        $escList[$row["EscalationTimeID"]]=new EscalationTimes();
                        $escList[$row["EscalationTimeID"]]->EscalationTimeID = $row["EscalationTimeID"];
                        $escList[$row["EscalationTimeID"]]->TimePeriod = $row["TimePeriod"];
                        $escList[$row["EscalationTimeID"]]->MakeDisplay();
                }

                return $escList;
        }

        function UpdatePeriod(){
                $this->MakeSafe();

                $oldperiod=new EscalationTimes();
                $oldperiod->EscalationTimeID=$this->EscalationTimeID;
                $oldperiod->GetEscalationTime();

                $sql="UPDATE fac_EscalationTimes SET TimePeriod=\"$this->TimePeriod\" WHERE
                        EscalationTimeID=$this->EscalationTimeID;";

                (class_exists('LogActions'))?LogActions::LogThis($this,$oldperiod):'';
                return $this->query($sql);
        }
}

?>
