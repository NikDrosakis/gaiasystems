<?php
$table=$this->method;
//xecho($_GET);
if(!isset($this->id)){
    $data= $this->db->fa("SELECT * FROM $table");

//many posts
}elseif($table=='post' && isset($this->id)){
    $data= $this->db->fa("SELECT post.*,postb.* FROM post LEFT join postb on postb.postid=post.id WHERE post.id=? ORDER BY postb.sort ASC ",[$this->id]);

}else{
    $data= $this->db->f("SELECT * FROM $table WHERE id=?",array($this->id));
}