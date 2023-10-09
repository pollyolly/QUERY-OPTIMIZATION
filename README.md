### QUERY-OPTIMIZATION

### Laravel
Eager Loading

### Python
Prefetch

### PHP
PHP EAGER LOADING
```php
$authors = $pdo->query("SELECT * FROM `authors` LIMIT 10;");

// Setup eager list of IDs
$ids = [];
foreach($authors as $author) {
    $ids[] = (int) $author['id'];
}
$author_ids = implode(',', $ids);

// Get all books and group them
$grouped_books = [];
$all_books = $pdo->query("SELECT * FROM `books` WHERE `author_id` IN ( {$author_ids} )");
foreach($all_books as $book) {
    $grouped_books[$book['author_id']][] = $book;
}

// Final loop
foreach ($authors as $author) {
    var_dump($author);
    var_dump($grouped_books[ $author['id'] ]);
}
```
CONCEPT OF EAGER LOADING
```php
<?php
class Template extends Database {

        public static function getTemplate($tid){
                $val = self::getRating($tid);
                if(is_null($val)){
                        echo "id: {$tid} feedback:  rating: \n";
                } else {
                        echo "id: {$tid} feedback: ".$val[1]." rating: ".$val[0]."\n";
                }
        }
}
class Database {

        private $strArr;
        public static $queryResult = array();

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

        public function selectIn(Database $db){
                //$ticketsid array() used for
                //SELECT rating FROM `tbl_rating` WHERE `ticket_id` IN ( {$ticketsid} )
                //return Dataset of results
                //Filter based on ID
                //echo "IN: ".$this->getStrArray()."\n";
                $sql = "SELECT `ticket_id`, `feedback`, `rating` FROM tbl_feedback WHERE `ticket_id` IN ( {$this->getStrArray()} )";
                $result = $db->dbconn()->query($sql);
                if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()) {
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
                $db->dbconn()->close(); //IF VIEW CALLED MULTIPLE
                                        //THIS WILL CAUSE ERROR
        }
       public static function getRating($tid){
                //var_dump(self::$queryResult);
                foreach(self::$queryResult as $rating){
                        if($rating['id'] == $tid){
                                return array($rating['rating'], $rating['feedback']);
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

        public static function ticketsView(){
                $tickets_arr = array(
                        array('id'=>34),
                        array('id'=>67),
                        array('id'=>90),
                        array('id'=>35),
                        array('id'=>23),
                        array('id'=>21),
                        array('id'=>78)
                );
                $ticketsid = array();
                foreach($tickets_arr as $tickets){
                        $ticketsid[] = $tickets['id'];
                }
                $sticketsid = implode(',',$ticketsid);

                $obj = self::getDbInstance();
                $obj->setStrArray($sticketsid);
                $obj->selectIn($obj);
                foreach($tickets_arr as $tickets){
                        self::getTemplate($tickets['id']);

                }
        }
}
TicketView::ticketsView();
```
### References

[Raw PHP Eager Loading](https://kevdees.com/raw-php-and-mysql-eager-loading/)

[Common Mistake in PHP](https://www.toptal.com/php/10-most-common-mistakes-php-programmers-make)
