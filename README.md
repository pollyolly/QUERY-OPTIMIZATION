### QUERY-OPTIMIZATION

### Laravel
Eager Loading

### Python
Prefetch

### PHP
PHP EAGER LOADING
```
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
```
class Template extends Database {
        public static function getTemplate($tid, $tmessage,$ticketsid){
                echo "id: {$tid} message: {$tmessage} rating: ".parent::getRating($tid,$ticketsid)."\n";
        }
}
class Database {
        public static function getRating($tid,$ticketsid){
                //$ticketsid array() used in SELECT
                //Onetime Query of Ratings: SELECT rating FROM `tbl_rating` WHERE `ticket_id` IN ( {$ticketsid} )                
                //Return Dataset of results sample below
                $rating_arr = array(
                        array('id'=>34, 'rating'=>5),
                        array('id'=>23, 'rating'=>3),
                        array('id'=>21, 'rating'=>2),
                        array('id'=>67, 'rating'=>1),
                        array('id'=>90, 'rating'=>4),
                        array('id'=>35, 'rating'=>3)
                );
                foreach($rating_arr as $rating){
                        if($rating['id'] === $tid){ //Filter based on ID
                                return $rating['rating']; //Return Rating
                        }
                }
        }
}
class TicketView extends Template {

        public static function ticketsView(){
                $tickets_arr = array(
                        array('id'=>34,'message'=>'hello'),
                        array('id'=>67,'message'=>'hi'),
                        array('id'=>90,'message'=>'what?'),
                        array('id'=>35,'message'=>'again?'),
                        array('id'=>23,'message'=>'come?'),
                        array('id'=>21,'message'=>'who you?')
                );
                $ticketsid = array();
                foreach($tickets_arr as $tickets){
                        $ticketsid[] = $tickets['id'];
                }
                foreach($tickets_arr as $tickets){
                        parent::getTemplate($tickets['id'],$tickets['message'], $ticketsid);
                }
        }
}
TicketView::ticketsView();
```
### References

[Raw PHP Eager Loading](https://kevdees.com/raw-php-and-mysql-eager-loading/)
