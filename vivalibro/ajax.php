<?php
$time=time();
$me=$this->G['my']['id'];

if($a=='booklist') { //f $file
     $buffer = $this->booklist();
     echo json_encode($buffer);

}elseif($a=='buffer') { //f $file
    $buffer = "";
    if (!ob_start("ob_gzhandler")) ob_start();
    $f = $_GET['f'];
    $pms = json_decode($_GET['pms'], true);
    $arg = $_GET['arg'];
    $argpms = json_decode($_GET['argpms'], true);
    $class = explode('/', $f)[count(explode('/', $f)) - 1];
    if ($arg == '' && !class_exists($class)) {
        $this->G['mode'] = $pms[0];
        $this->G['param'] = $pms[1];
        include_once $f . '.php';
    } else { //class
        //$cont = new $_GET['f']($pms);
        $inst = $this->classInstance($class, $pms);
        if ($arg != '' && $argpms != '') {
            $inst->$arg(implode(',', $argpms));
        }
    }
    $buffer = ob_get_clean();
    flush();
    ob_end_clean();
    echo $buffer;
}elseif($a=='func2'){
    //b:method c:param
     $cq = $b == 'fetchList1' ? explode(',', $c) : $c;
	if(in_array($b,array("name_not_exist","validate"))){
		$sel = $this->$b($cq);
		echo $sel ? json_encode("ok"): json_encode("no");
    }else{
        $sel = $this->db->$b($cq);
		echo $b=="q" && $sel ? json_encode("yes") : json_encode($sel);
		//echo $b!='get' ? ($b=='query' && $sel ? json_encode("yes") : json_encode($sel)) :$sel;
    }    


}elseif($a=='func') {
$cq = $b == 'fl' ? explode(',', $c) : $c;
$data = $this->db->$b($cq);
echo $b=="q" && $data ? json_encode("yes") : json_encode($data);

}elseif($a=='comment') { //{a:"comment",content:$('#').val(),reply_id:$(this).attr('reply_id'),type:'book',typeid:G.id,uid:G.my.id};
    unset($_POST['a']);
    $q= $this->db->inse("comment",$_POST);
    echo !$q ? "NO":$q;
}elseif($a=='bookedit') { //$b:param $c:id of writer $d:id of book
	$q= $this->db->q("UPDATE vl_book set $b=? WHERE id=?",[$_GET['val'],$_GET['id']]);
	echo !$q ? "NO":"OK";
}elseif($a=='save') { //$col
    $col=$_POST['col'];
    if($col=='summary') {
        $q = $this->db->q("UPDATE vl_book set $col=? WHERE id=?", [$_POST['val'], $_POST['id']]);
    }elseif($col=='notes') {
        $q = $this->db->q("UPDATE vl_libuser set $col=? WHERE bookid=?", [$_POST['val'], $_POST['id']]);
    }
    echo !$q ? "NO":"OK";
}elseif($a=='lookupsave') { //$b:param $c:id of writer $d:id of book
	$q= $this->db->q("UPDATE vl_book set $b=? WHERE id=?",[$c,$_GET['id']]);
	echo !$q ? "NO":"OK";
	
}elseif($a=='lookup') {
	$sel= $this->db->fl(["name","id"],"vl_$b","WHERE name LIKE '%$c%' ORDER BY name");
	echo json_encode($sel);
}elseif($a=='new') {
		   $ins=$this->db->inse("vl_$b",["name"=>$c]);
		   	$q= $this->db->q("UPDATE vl_book set $b=? WHERE id=?",[$ins,$_GET['id']]);
		   	echo $q && $ins ? "OK":"NO";	
}elseif($a=='newbks') {
	  $book=array('title'=> "new book");
      $insert=$this->db->inse('vl_book',$book);
	echo !$insert ? "NO":$insert;	   
}elseif($a=='rating'){
    $stars=$_POST['stars'];
    $id=$_POST['id'];
    $select=$this->db->f("SELECT uid FROM vl_book_rating WHERE bookid=? AND uid=?",[$id,$me]);
    if(!empty($select)){
        $query=$this->db->q("UPDATE vl_book_rating SET stars=? WHERE bookid=? and uid=?",[$stars,$id,$me]);
    }else{
        $query=$this->db->inse('vl_book_rating',['uid'=>$me,'bookid'=>$id,'stars'=>$stars,'created'=>$time]);
    }
echo !$query ? 'NO':'OK';
}elseif($a=='newbkscat'){
	if($_POST['name']!=''){
	   $cat=array('name'=>$_POST['name'],'parent'=>$_POST['parent']);
	   $inscat=$this->db->inse('cat',$cat);
	   echo json_encode($inscat);	   
	}
}elseif($a=='del'){
	$b=!empty($b) ? "vl_".$b : 'vl_book';
	$this->db->q("DELETE FROM $b WHERE id=$c");

}elseif($a=='copy'){
   if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['url'])){
       $url=$_POST['url'];
       $path = pathinfo($url);
       $ext=!empty($path['extension']) ? explode('?',$path['extension'])[0] : 'jpg';
       $img=$_POST['name'].'.'.$ext;
       if(copy($_POST['url'],$this->G['GAIABASE']."media/" .$img)){
           //save to db
           $id=(int)$_POST['id'];
		   $table=$_POST['table'];
			$query="UPDATE $table SET img=? WHERE id=?";
			$q=$this->db->q($query,[$img,$id]);
            if($q) {
                echo $query;
            }else{echo $query;}
       }else{
           echo $query;
       }
   }
}elseif($a=='cachereset'){
    $output=array();
    $output[]= opcache_reset();
//    $redispass = $this->GLOBAL['CONF']['redis_pass'];
//       $output[] = shell_exec("redis-cli -a $redispass flushall");
    echo implode('',$output);

    //$siteroot= SITE_ROOT.'gaia/c/test.c';
    //shell_exec("g++ $siteroot -o test1");
    //echo exec(SITE_ROOT.'gaia/c/test1');
}

