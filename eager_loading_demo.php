<?php
class Template extends Database {
        public function getFeedbacks($tid){
        //public static function getTemplate($tid){
                $val = $this->getRating($tid);
                //$val = self::getRating($tid);
                if(is_null($val)){
                        echo "<tr><td> id: {$tid} </td><td>feedback: </td><td> rating: </td></tr>";
                } else {
                        echo "<tr><td> id: {$tid} </td><td> feedback: ".$val[1]." </td><td> rating: ".$val[0]."</td></tr>";
                }
        }
        public function getTickets($tno){
                $val = $this->getTicketNumber($tno);
                //$val = self::getRating($tid);
                if(is_null($val)){
                        echo "<tr><td> ticket_number: {$tno} </td><td> ticket_content: </td><td> ticket_status: </td></tr>";
                } else {
                        echo "<tr><td> ticket_number: {$tno} </td> <td>ticket_content: ".$val[0]." </td><td>ticket_status: ".$val[1]."</td></tr>";
                }
        }
}
class Database {

        private $strArr;
        public static $queryResult = array();
        //public $queryResult = array();
        public $connection;

        function __construct(){
                $this->connection = new mysqli('localhost', 'root', 'Phpmy4dm1n##','optimized_query');
        }

        public function dbconn(){
                return $this->connection;
        }

        public function setStrArray($strArr){
                $this->strArr = $strArr;
        }

        public function getStrArray(){
                return $this->strArr;
        }

        public function selectFeedback(Database $db){
                //$ticketsid array() used for
                //SELECT rating FROM `tbl_rating` WHERE `ticket_id` IN ( {$ticketsid} )
                //return Dataset of results
                //Filter based on ID
                //echo "IN: ".$this->getStrArray()."\n";
                $sql = "SELECT `ticket_id`, `feedback`, `rating` FROM tbl_feedback WHERE `ticket_id` IN ( {$this->getStrArray()} )";
                $result = $db->dbconn()->query($sql);
                if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()) {
                                //self::$queryResult[] =array('id'=>$row['ticket_id'],'feedback'=>$row['feedback'], 'rating'=>$row['rating']);
                                self::$queryResult[] =array('id'=>$row['ticket_id'],'feedback'=>$row['feedback'], 'rating'=>$row['rating']);

                                //var_dump($row);
                        }
                }
          
               //var_dump(self::$queryResult);
                /*self::$queryResult = array(
                        array('id'=>34, 'rating'=>5, 'feedback'=>'very good!'),
                        array('id'=>23, 'rating'=>3, 'feedback'=>'fair'),
                        array('id'=>21, 'rating'=>2, 'feedback'=>'not bad'),
                        array('id'=>67, 'rating'=>1, 'feedback'=>'bad'),
                        array('id'=>90, 'rating'=>4, 'feedback'=>'good'),
                        array('id'=>35, 'rating'=>3, 'feedback'=>'fair')
                );*/
                //$db->dbconn()->close(); //IF VIEW CALLED MULTIPLE
                                        //THIS WILL CAUSE ERROR
        }

        public function selectTicket(Database $db){
                $sql = "SELECT `ticket_number`, `ticket_content`, `ticket_status` FROM tbl_ticket WHERE `ticket_number` IN ( {$this->getStrArray()} )";
                $result = $db->dbconn()->query($sql);
                if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()) {
                                //self::$queryResult[] =array('id'=>$row['ticket_id'],'feedback'=>$row['feedback'], 'rating'=>$row['rating']);
                                self::$queryResult[] =array('ticket_number'=>$row['ticket_number'],'ticket_content'=>$row['ticket_content'], 'ticket_status'=>$row['ticket_status']);

                                //var_dump($row);
                        }
                }
        }

        public function getRating($tid){
        //public static function getRating($tid){

                //var_dump(self::$queryResult);
                //foreach(self::$queryResult as $rating){
                foreach(self::$queryResult as $rating){
                        if($rating['id'] == $tid){
                                return array($rating['rating'], $rating['feedback']);
                        }
                }
        }

        public function getTicketNumber($tno){
                foreach(self::$queryResult as $ticket){
                        if($ticket['ticket_number'] == $tno){
                                return array($ticket['ticket_content'], $ticket['ticket_status']);
                        }
                }
        }
}
trait dbInstances {
        public static $db;

        public static function getDbInstance(){
                if(null === self::$db){
                        self::$db = new Database();
                }
                return self::$db;
        }
}
class TicketView extends Template {
        use dbInstances;

        public function ticketViews(){
                $tickets_no = array(
                        array('ticket_number'=>3435343),
                        array('ticket_number'=>5675543),
                        array('ticket_number'=>2323423),
                        array('ticket_number'=>5653444),
                        array('ticket_number'=>3463269),
                        array('ticket_number'=>111111),
                );
                $ticketsno = array();
                foreach($tickets_no as $tickets){
                        $ticketsno[] = $tickets['ticket_number'];
                }
                $sticketsno = implode(',',$ticketsno);

                $obj = self::getDbInstance();
                $obj->setStrArray($sticketsno);
                $obj->selectTicket($obj);
                foreach($tickets_no as $tickets){
                        $this->getTickets($tickets['ticket_number']);
                        //self::getTemplate($tickets['id']);
                }
        }
        public function feedbackView(){
                $tickets_arr = array(
                        array('id'=>21),
                        array('id'=>34),
                        array('id'=>67),
                        array('id'=>90),
                        array('id'=>35),
                        array('id'=>23),
                        array('id'=>78)
                );
                $ticketsid = array();
                foreach($tickets_arr as $tickets){
                        $ticketsid[] = $tickets['id'];
                }
                $sticketsid = implode(',',$ticketsid);

                $obj = self::getDbInstance();
                $obj->setStrArray($sticketsid);
                $obj->selectFeedback($obj);
                foreach($tickets_arr as $tickets){
                        $this->getFeedbacks($tickets['id']);
                        //self::getTemplate($tickets['id']);
                }
        }
}
$obj = new TicketView();
?>
<html>
        <head>
                <title>Test</title>
        </head>
        <style>
                table, td, th, tr {
                        border:#ccc 1px solid;
                        border-collapse: collapse;
                }
        </style>
        <body>
                <h2>Feedback</h2>
                <table>
                <?php $obj->feedbackView(); ?>
                </table>
                <h2>Ticket</h2>
                <table>
                <?php $obj->ticketViews(); ?>
                </table>
        </body>
</html>
