<?php 
if($a=='widget_save'){
    $select=$this->db->f("SELECT en FROM globs WHERE name=? AND tag=?",[$b,'layout']);
    if(!empty($select)){
        $query=$this->db->q("UPDATE globs SET en=? WHERE name=? and tag=?",[$c,$b,'layout']);
    }else{
        $query=$this->db->inse('globs',['name'=>$b,'en'=>$c,'tag'=>'layout']);
    }

}elseif($a=='widget_get'){
    $select=$this->db->f("SELECT en FROM globs WHERE name=? AND tag=?",[$b,'layout']);
    echo !$select ? json_encode('NO'): json_encode($select['en']);
}
?>