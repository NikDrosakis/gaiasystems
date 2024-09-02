<?php
namespace Core;

class Vivalibro extends Gaia {
 use GPM;
 public function handleRequest() {
   if ($this->isApiRequest()) { // Now calls isApiRequest() from Gaia
             $this->start_API();
     } elseif ($this->isXHRRequest()) {
               $this->handleXHRRequest();
        } else if($this->isCuboRequest()){
          $this->handleCuboRequest();
        } else if($this->isWorkerRequest()){
                $this->handleWorkerRequest();
//             xecho($_GET);
        }else{
            // VL-specific normal request handling:
            if ($this->CUR_DIR == 'admin') {
                $this->start_adminUI();
                //xecho($this->G);
            }else{
                $this->start_publicUI();
                  //  xecho($this->G);
                  }
        }
    }


    // Method to generate a list of books based on the current page type
   protected function booklist() {
        $pagin = 12; // Pagination: number of results per page
        $start = ($_GET['pagenum'] - 1) * $pagin;
        $limit = " LIMIT $start, $pagin";
        $q = isset($_GET['q']) ? $_GET['q'] : '';
        $buffer = array();
        $sel = array(); 

        if ($this->page == 'ebook') {
            $name = isset($_GET['name']) ? $_GET['name'] : '';
            $ebooks = glob("/pdf/$name/*.pdf");
            if (!empty($ebooks)) {
                foreach (array_slice($ebooks, $start, $pagin) as $i => $e) {
                    $e = basename($e);
                    $sel[$i]['title'] = basename($e, ".pdf");
                    $sel[$i]['booklink'] = "/pdf/$name/$e";
                }
                $buffer['count'] = count($ebooks);
            } else {
                $buffer['count'] = 0;
            }
        } elseif ($this->page == "book" || $this->G['page'] == "books") {
            $orderby = !empty($_COOKIE['orderby']) ? $_COOKIE['orderby'] : "RAND()";
            $langQ = !empty($_COOKIE['LANG']) ? "AND vl_book.lang='" . $_COOKIE['LANG'] . "'" : "";
            $libQ = $this->page == "book" ? "AND vl_libuser.libid=" . $this->libid : "";
            $tableQ = $this->page == "book" ? "FROM vl_libuser LEFT JOIN vl_book ON vl_libuser.bookid=vl_book.id" : "FROM vl_book";
            $qQ = $q != "" ? "AND (vl_book.title LIKE '%$q%' OR vl_writer.name LIKE '%$q%' OR vl_cat.name LIKE '%$q%' OR vl_publisher.name LIKE '%$q%')" : "";

            $query = "SELECT vl_book.*, CONCAT('/book?id=', vl_book.id) AS booklink, vl_writer.name AS writername, vl_cat.name AS catname, vl_publisher.name AS publishername 
                      $tableQ 
                      LEFT JOIN vl_writer ON vl_book.writer = vl_writer.id 
                      LEFT JOIN vl_cat ON vl_book.cat = vl_cat.id 
                      LEFT JOIN vl_publisher ON vl_book.publisher = vl_publisher.id 
                      WHERE vl_book.img IS NOT NULL $langQ $libQ $qQ 
                      ORDER BY $orderby";

            $sel = $this->db->fa("$query $limit");
            $count = count($this->db->fa($query));
        } elseif ($this->page == "libraries") {
            $sel = $this->get_libs();
            $count = count($sel);
        } elseif ($this->page == "writer") {
            $query = "SELECT * FROM vl_writer ORDER BY name DESC";
            $list = $this->db->fa("$query $limit");
            foreach ($list as $i => $writer) {
                $sel[$i] = $writer;
                $id = (int) $writer['id'];
                $sel[$i]['books'] = $this->db->fl(["id", "title"], "vl_book", "WHERE writer=$id");
                $sel[$i]['categories'] = $this->db->fl(["cat.id", "cat.name"], "vl_cat", "LEFT JOIN vl_book ON vl_book.cat=vl_cat.id WHERE vl_book.writer=$id");
            }
            $count = count($this->db->fa($query));
        } elseif ($this->page == "publisher") {
            $query = "SELECT * FROM vl_publisher ORDER BY name DESC";
            $list = $this->db->fa("$query $limit");
            foreach ($list as $i => $publisher) {
                $sel[$i] = $publisher;
                $id = (int) $publisher['id'];
                $sel[$i]['books'] = $this->db->fl(["id", "title"], "vl_book", "WHERE vl_publisher=$id");
                $sel[$i]['categories'] = $this->db->fl(["vl_cat.id", "vl_cat.name"], "vl_cat", "LEFT JOIN vl_book ON vl_book.cat=cat.id WHERE vl_book.writer=$id");
            }
            $count = count($this->db->fa($query));
        }

        // Create buffer for output
        $buffer['count'] = $count;
        $buffer['list'] = $sel;
        $buffer['titles'] = array_map(function($item) {
            return $this->page == "book" || $this->page == "books" ? $item['title'] : $item['name'];
        }, $sel);
        $buffer['html'] = include_buffer(SITE_ROOT . "main_buffers/" . $this->page . "_archive.php", $sel);
        return $buffer;
    }

    // Method to retrieve all categories
   private function get_categories() {
        return $this->db->fa("SELECT * FROM vl_cat");
    }

    // Method to retrieve all libraries
   private function get_libs() {
        return $this->db->fa("SELECT * FROM vl_lib");
    }

    // Method to retrieve the current user's library
   private function get_mylib() {
        return $this->db->f("SELECT * FROM vl_lib WHERE userid=?", array($this->me));
    }

    // Method to retrieve a specific book's details
   private function get_book() {
        return $this->db->f("SELECT vl_book.*, vl_libuser.notes, vl_libuser.isread,
                             vl_book_rating.stars, vl_writer.name AS writer, 
                             vl_cat.name AS cat, vl_publisher.name AS publisher 
                             FROM vl_book 
                             LEFT JOIN vl_book_rating ON vl_book.id=vl_book_rating.bookid AND vl_book_rating.uid=? 
                             LEFT JOIN vl_writer ON vl_book.writer=vl_writer.id 
                             LEFT JOIN vl_cat ON vl_book.cat=vl_cat.id 
                             LEFT JOIN vl_libuser ON vl_book.id=vl_libuser.bookid 
                             LEFT JOIN vl_publisher ON vl_book.publisher=vl_publisher.id 
                             WHERE vl_book.id=?", array($this->me, $this->id));
    }

    // Method to retrieve comments for a specific type and ID
   private function get_comments($type = 'book') {
        $sel = $this->db->fa("SELECT comment.*, CONCAT(user.firstname, ' ', user.lastname) AS fullname, user.img
                              FROM comment 
                              LEFT JOIN user ON comment.uid=user.id 
                              WHERE comment.type=? AND comment.typeid=? AND comment.reply_id=0 
                              ORDER BY comment.created DESC", [$type, $_GET['id']]);

        // Insert replies into comments
        if (!empty($sel)) {
            foreach ($sel as $i => $comment) {
                $sel[$i]['replies'] = $this->db->fa("SELECT comment.*, CONCAT(user.firstname, ' ', user.lastname) AS fullname, user.img
                                                     FROM comment 
                                                     LEFT JOIN user ON comment.uid=user.id 
                                                     WHERE comment.reply_id=? 
                                                     ORDER BY comment.created DESC", [$comment['id']]);
            }
        }

        return $sel;
    }
}
