<?php
namespace Core;

abstract class Gaia {
 use GPM;
     protected $db;
       protected $dbm;
	protected $redis;
//api
    protected $response = [];
    public $verb;
    public $method;
    public $endpoint;

//other
    public $template;
    public $connect;
    public $agent;
    public $tax; //instantiate Taxonomy Class
    public $user; //instantiate User Class
    public $conf;
    public $confd;

    public $superusers;
    public $taxname;
    public $apages;
    public $G;
    public $me;
    public $fullname;
    public $img;

    public $WIDGETURI;
    public $MAINURI;
    public $LOC;
    public $LIB;
    public $IMG;
    public $TEMPLATE ;
    public $ADMIN_ROOT;
    public $CUR_DIR;
    public $SITE_ROOT;
    public $SITE_URL;

    public $id;
    public $uid;
    public $page; //instantiate Page Class
    public $mode;
    public $sub;
    public $subs;
    public $icons;
    public $usergrps;
    public $is;
    public $aconf;
    
    public function __construct() {
	include_once ADMIN_ROOT.'config.php';
	include_once ADMIN_ROOT."generic.php";
        /*****
         * START MARIADB
         *****/
        $this->db = new Maria(TEMPLATE);
		/*****
         * START GAIA PACKAGE MANAGER		
         *****/
       $this->dbm = new Maria("gpm");
//		$this->gpm = new GPM();
        /*****
         * START REDIS
         *****/
        $this->redis=new Gredis("1");


        $this->G['loggedin'] = isset($_COOKIE['GSID']) && $_COOKIE['GSGRP'] > 1;
        if ($this->G['loggedin']) {
            $this->G['my'] = $this->db->f("SELECT * from user where id=?", [$_COOKIE['GSID']]);
            $this->me = $this->G['my']['id'];
            $this->fullname = $this->G['my']['fullname'] = $this->G['my']['firstname'] . ' ' . $this->G['my']['lastname'];
            $this->img = "/media/" . $this->G['my']['img'];
        }
		$this->G['is'] = $this->redis->get("is");
		if(!$this->G['is']){        
        $this->G['is'] = $this->db->fl(["name", LOC], "globs");
		$this->redis->set("is",$this->G['is']);
		}


		foreach(glob(CUBOS_ROOT.'*',GLOB_ONLYDIR) as $wdi =>$wdv){
			$this->G['widgetlist'][$wdi]= basename($wdv);
		}
        if (!empty($this->G['id'])) {
            $widgets_select = $this->db->f("select en from globs where name=? and tag=?", ['book_' . $this->page, 'layout']);
        } else {
            $widgets_select = $this->db->f("select en from globs where name=? and tag=?", [$this->G['page'], 'layout']);
        }

        if (!empty($widgets_select)) {
            $this->G['widgets'] = json_decode($widgets_select['en'], true);
        }

     //   $this->G['postgrps'] = $this->db->fl(["id", "name"], "postgrp", "WHERE status=1");
        $this->G['usergrps'] = $this->db->fl(["id", "name"], "usergrp");
       // $this->G['taxgrps'] = $this->db->fl(["name", "parenting"], "taxgrp", "WHERE status=1");
        //$this->G['taxgrp'] = $this->db->fl(["id", "name"], "taxgrp", "WHERE status=1");
        //$this->G['posts'] = $this->db->fl(["id", "title"], "post");
        //$this->G['users'] = $this->db->fl(["id", "name"], "user");
        $this->G['menus'] = $this->db->fl(["id", "title"], "linksgrp");
        $this->G['supusers'] = $this->db->fl(["id", "name"], "user", "WHERE grp > 1");
        $this->G['taxname'] = $this->db->fl(["id", "name"], "tax");
        $this->G['globs_tags'] = array_values(array_unique($this->db->fl('tag', 'globs')));


$this->G['apages'] = [
    "notification" => ["title" => "Notification", "subs" => [], "icon" => "bell"],  // Glyphicon: bell
    "gpm" => ["title" => "GPM", "subs" => ["Timetable"=>"timetable", "Metrics"=>"metric"], "icon" => "stats"],  // Glyphicon: stats
    "apiws" => ["title" => "APIWSIntegrator", "subs" => [], "icon" => "transfer"],  // Glyphicon: transfer
    "cubos" => ["title" => "Cubos", "subs" => $this->widgets_admin($this->G), "icon" => "th-large"],  // Glyphicon: th-large
    "app" => ["title" => "Mobile App", "subs" => [], "icon" => "phone"],  // Glyphicon: phone
    "games" => ["title" => "Games", "subs" => [], "icon" => "king"],  // Glyphicon: king
    "menu" => ["title" => "Menu", "subs" => [], "icon" => "list"],  // Glyphicon: list
    "setup" => ["title" => "Setup", "subs" => ["New domain" => "new", "Templates" => "templates", "Info" => "info"], "icon" => "cog"],  // Glyphicon: cog
    "tools" => ["title" => "DBS & Third Tools", "subs" => [
        "MariaDB" => "https://vivalibro.com/tools/phpmyadmin/",
        "Opcache" => "https://vivalibro.com/tools/opcache/",
        "RedisDB" => "https://vivalibro.com/tools/phpRedisAdmin/?view",
        "Sqlite" => "https://vivalibro.com/tools/sqlite/",
        "Solr" => "http://vivalibro.com:8983/solr/#/",
        "Neo4j" => "http://vivalibro.com:7474/browser/"
    ], "icon" => "wrench"],  // Glyphicon: wrench
    "user" => ["title" => "User", "subs" => [], "icon" => "user"],  // Glyphicon: user
    "post" => ["title" => "Post", "subs" => [], "icon" => "pencil"],  // Glyphicon: pencil
    "tax" => ["title" => "Taxonomy", "subs" => [], "icon" => "tags"],  // Glyphicon: tags
    "media" => ["title" => "Media", "subs" => ["Check DB" => "checkdb", "Chunking" => "chunk"], "icon" => "film"],  // Glyphicon: film
    "local" => ["title" => "Localization", "subs" => [], "icon" => "globe"],  // Glyphicon: globe
    "seo" => ["title" => "SEO", "subs" => [], "icon" => "signal"],  // Glyphicon: signal
    "advertisements" => ["title" => "Advertisement", "subs" => [], "icon" => "bullhorn"]  // Glyphicon: bullhorn
];

//all $this->G to local variables 
        foreach ($this->G as $gkey => $gval) {
		if (property_exists($this, $gkey)) {
			$this->$gkey = $gval;
		}
        }
        // Handle requests (delegated to child classes)
        $this->handleRequest();
    }
    abstract protected function handleRequest();

    protected function isCuboRequest(): bool {
        return $this->CUR_DIR=== 'cubos';
    }
    protected function isWorkerRequest(): bool {
        return isset($_GET['isWorkerRequest']) && $_GET['isWorkerRequest'] === 'true';
    }
    protected function isXHRRequest(): bool {
            return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                   strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        }
     protected function start_API() {
            $response = $this->getResponse();
           echo json_encode($response, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        }

    // API Interaction Methods
     protected function isApiRequest(): bool {
            // Check if the request is made to the /apiv1 path
            return strpos($_SERVER['REQUEST_URI'], '/apiv1') === 0;
        }

//https://vivalibro.com/cubos/index.php?cubo=menuweb&file=public.php
protected function handleCuboRequest(): void {
    // Define the root directory for cubos
    $cubosRoot = $this->G['CUBOS_ROOT'];

    // Retrieve and sanitize the file path parameters
    $cubo = basename($_GET['cubo'] ?? '');
    $file = basename($_GET['file'] ?? '');

    // Construct the full file path
    $filePath = $cubosRoot . '/' . $cubo . '/' . $file;

    // Check if the file exists and is within the expected directory
    if (file_exists($filePath) && strpos(realpath($filePath), realpath($cubosRoot)) === 0) {
        include $filePath;
    } else {
        // Handle the error if the file is not found or is not valid
        http_response_code(404);
        echo '<div class="error">File not found or access denied.</div>';
    }
}
/*
Using non blocking features of web worker
to be updated to $_post AND json content
 */
    protected function handleWorkerRequest():void{
   try {
          // Start output buffering with gzip compression if supported
           $buffer='';

          ob_start();
          // Not necessary params
        $a = $_POST['a'] ?? $_GET['a'] ?? null;
        $b = $_POST['b'] ?? $_GET['b'] ?? null;
        $c = $_POST['c'] ?? $_GET['c'] ?? null;
        $d = $_POST['d'] ?? $_GET['d'] ?? null;
          $file = $_GET['file'] ?? '';
          // Include the specified file
          if (file_exists($file . '.php')) {
              include $file . '.php';
          } else {
              $buffer = "File not found: " . htmlspecialchars($file);
          }
          // Capture HTML content
          $buffer = ob_get_clean();
      } catch (Exception $e) {
          // Handle exception and set error in buffer
          $buffer = $e->getMessage();
      }
      //HTML content
    header('Content-Type: text/html');
    echo $buffer;
    }

 /**
      * Handle XHR request.
     */
     protected function handleXHRRequest(): void {
        // Example: Route the request to a specific method based on a parameter
        $a = $_POST['a'] ?? $_GET['a'] ?? null;
        $b = $_POST['b'] ?? $_GET['b'] ?? null;
        $c = $_POST['c'] ?? $_GET['c'] ?? null;
        $d = $_POST['d'] ?? $_GET['d'] ?? null;
        // CUSTOM FILE ELSE DEFAULT AJAX.PHP FOR PUBLIC AND XHR FOR ADMIN
        $file = $_POST['file'] ?? $_GET['file'] ?? ($this->CUR_DIR=='admin' ? $this->ADMIN_ROOT."xhr.php" : $this->SITE_ROOT."ajax.php");

		if ($file && file_exists($file)) {

        try {
            include $file;
        } catch (Exception $e) {
            error_log("Error including file: " . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
        } else {
            error_log("File not found: " . $file);
            echo json_encode(['error' => "File not found: " . $file]);
        }

    }

 protected function getResponse(){
       $this->method = $_SERVER['REQUEST_METHOD'];
       $this->endpoint = $_GET['endpoint'];
        foreach($_REQUEST as $key =>$value){
            $this->G[$key]=$value;
        }
       $response = array();
       if (empty($this->endpoint)){
            $status=400;
         } else {
            $token=base64_encode('nikos:130177'); //read token from db bXlzZWNyZXR0b2tlbjE3
                     $response['data']= $this->getResponseData();
                        if (!empty($response['data'])) {
                            $status = 200;
                            $status_message = $this->G['status_message'][$status];
                        }else if (!$response['data']) {
							$status = 500;
                            $status_message = 'Query is not correct';
						} elseif(empty($response['data'])){
                            $status = 200;
                            $status_message = 'Νο results found';
                        } else{
                           $status = 200;
                           $status_message = 'success';
                        }
        }
            $status_message= $this->G['status_message'][$status];
                    header("HTTP/2 $status $status_message");
                    header("Content-Type: application/json; charset=UTF-8");
                    $response['status'] = $status;
                    $response['status_message'] = $status_message;
                    $response['method'] = $this->method;
                    $response['request'] = $_REQUEST;
                return $response;
    }
    /*
     * API METHODS
     * */
    protected function getResponseData()   {
        if (file_exists(ADMIN_ROOT . $this->method."/".$this->endpoint.".php")) {
                require_once ADMIN_ROOT. $this->method."/".$this->endpoint.".php";
            }
            return $data;
    }
    protected function start_publicUI() {
    		include PUBLIC_ROOT_WEB."components/head.php";
    		include PUBLIC_ROOT_WEB."components/body.php";
    		//load all widgets of the page to the body

   	}
    protected function start_adminUI() {
 		$this->subs=$this->apages[$this->page]['subs'];
        include $this->SITE_ROOT . "components/head.php";
       include $this->SITE_ROOT . "components/dshbar.php";
        echo '<div id="container">';
        echo '<div id="mainpage">';

		//INCLUDE widgets/administrator
        if ($this->page == 'cubos' && $this->sub!="") {
            if (file_exists($this->WIDGETURI .$this->page . "/admin.php")) {
                include $this->WIDGETURI .$this->page . "/admin.php";
            }
        } else {
        //INCLUDE admin subpages /admin/[page]/[sub]
		if (!empty($this->sub)) {
                if (file_exists($this->SITE_ROOT . "main/" . $this->page . "/" .$this->sub . ".php")) {
                    include $this->SITE_ROOT . "main/" . $this->page . "/" .$this->sub . ".php";
			//INCLUDE admin subpages /admin/[mode]/[mode].php
                } else {
                    include $this->SITE_ROOT . "main/" . $this->page . "/" . $this->page . ".php";
                }
		//INCLUDE admin subpages /admin/[mode]/[mode].php
		} else {
                include $this->SITE_ROOT . "main/" . $this->page. "/" . $this->page . ".php";
            }
        }
        echo "</div>";
        include $this->SITE_ROOT . "components/footer.php";
    }

     /**
     * Fetch data for XHR request.
     */

private function getIcon($title):string{
    return $this->icons[$title] ?? "asterisk";
	}

    private function widgets() {
        $widgets = read_folder($this->WIDGETURI);
        sort($widgets);
        return $widgets;
    }

    private function widgets_admin() {
        $list = [];
        $widgets = read_folder($this->WIDGETURI);
        foreach ($widgets as $wid) {
            if (file_exists($this->WIDGETURI . $wid . "/admin.php")) {
                $list[$wid] = $wid;
            }
        }
        return $list;
    }

    private function get_links() {
        return $this->db->fa("SELECT * FROM links WHERE linksgrpid=2 ORDER BY sort");
    }

    private function get_users() {
        return $this->db->fa("SELECT * FROM user");
    }

    private function inside() {
        if (!empty($_COOKIE['GSID'])) {
            $phase = $this->db->f("SELECT phase FROM user WHERE id=?", [$_COOKIE['GSID']])['phase'];
            return $phase == 2;
        }
        return false;
    }

    private function islogged() {
        if (!empty($_COOKIE['sp']) && !empty($_COOKIE['GSID'])) {
            $user = $this->db->f("SELECT phase, sp FROM user WHERE id=?", [$_COOKIE['GSID']]);
            return !empty($user) && $user['phase'] != 0 && $user['sp'] == $_COOKIE['sp'];
        }
        return false;
    }

    private function login($pass, $mail) {
        $fetch = $this->db->f("SELECT * FROM user WHERE mail=? AND pass=?", [$mail, $pass]);

        if (empty($fetch)) {
            return 'no_account';
        } elseif (!in_array($fetch['auth'], ['1', '2', '3', '4', '5'])) {
            return 'Authentication Pending';
        } elseif ($fetch['auth'] != '1') {
            return $fetch;
        } else {
            $hash = ($fetch['phase'] == 2) ? ($fetch['sp'] != 0 ? $fetch['sp'] : hash("sha256", $fetch['id'] . time())) : hash("sha256", $fetch['id'] . time());
            $fetch['sp'] = $hash;
            $updatePhase = $this->db->q("UPDATE user SET phase=?, last_login=? WHERE id=?", [2, time(), $fetch['id']]);
            return $updatePhase ? $fetch : "mistake";
        }
    }

    private function logout() {
        $logout = $this->db->q("UPDATE user SET phase=0 WHERE id=?", [$_COOKIE['GSID']]);
        return $logout ? true : false;
    }

    private function isAuthorised($level = []) {
        if ($this->inside()) {
            return in_array($_COOKIE['GSGRP'], $level);
        }
        return false;
    }

    private function validate($pa) {
        if (filter_var($pa, FILTER_VALIDATE_EMAIL)) {
            $mailExist = $this->db->count_("id", "user", " WHERE mail='$pa'");
            return $mailExist == 0;
        }
        return false;
    }

    private function nameNotExist($pa) {
        $nameExist = $this->db->count_("id", "user", " WHERE name='$pa'");
        return $nameExist == 0;
    }

    private function form($table,$schema,$cols = [], $form = false, $res = []) {
        $return = '';
        $type = $schema[$table];
        $id = $res['id'];
        $img = empty($res['img']) ? "/admin/img/post.jpg" : UPLOADS . $res['img'];

        if ($form) {
            $return .= "<form id='form_$table'><input type='hidden' name='a' value='new'>";
            $return .= "<input type='hidden' name='table' value='$table'>";
        }

        foreach ($cols as $col) {
            $typ = explode('-', $type[$col]);
            $typ1 = !empty($typ) ? $typ[0] : $type[$col];
            $typ2 = !empty($typ) ? $typ[1] : false;
            if (strpos($typ2, '.') !== false) {
                [$typ2table, $typ2row] = explode('.', $typ2);
                $res[$col] = $this->db->f("SELECT $typ2row FROM $typ2table WHERE id=?", [$res[$col]])[$typ2row];
            }

            switch ($typ1) {
                case 'text':
                    $return .= "<div class='gs-span'><label for='$col'>$col</label>
                    <input class='gs-input' name='$col' placeholder='$col' id='$col' type='text' value='$res[$col]'></div>";
                    break;
                case 'number':
                    $return .= "<div class='gs-span'><label for='$col'>$col</label>
                    <input class='gs-input' name='$col' placeholder='$col' id='$col' type='number' value='$res[$col]'></div>";
                    break;
                case 'img':
                    $return .= "<div class=\"imgBox\">
                    <div id=\"files\" class=\"files\" style=\"\"><img src='$img' style=\"height:250px;width: 229px;margin: -21px 0 0 -21px;\"></div>
                    <span class=\"btn btn-success btn-sm fileinput-button\">
                    <i class=\"glyphicon glyphicon-plus\"></i>
                    <span>Add file</span>
                    <input id=\"fileupload\" type=\"file\" name=\"files\">
                    </span>
                    <div id=\"progress\" class=\"progress\" style=\"display:none\">
                    <div class=\"progress-bar progress-bar-success\"></div>
                    </div>
                    <button id=\"selectimage\" onclick=\"s.media.select(this)\" class=\"btn btn-info btn-sm\">Select</button>
                    </div>";
                    break;
                case 'select':
                    $return .= "<div class='gs-span'><label for='$col'>$col</label><select class='gs-input' name='$col' id='$col'>";
                    if (!empty($this->$typ2)) {
                        foreach ($this->$typ2 as $li => $liname) {
                            $selected = $res[$col] == $li ? "selected=selected" : "";
                            $return .= "<option value='$li' $selected>$liname</option>";
                        }
                    }
                    $return .= "</select></div>";
                    break;
                case 'textarea':
                    $return .= "<div class='gs-span'><label for='$col'>$col</label>";
                    if ($typ2 == 'editor') {
                        $return .= "<textarea class='editor' name='$col'>$res[$col]</textarea>";
                    } else {
                        $return .= "<textarea class='gs-input' name='$col' placeholder='$col' id='$col'>$res[$col]</textarea>";
                    }
                    $return .= "</div>";
                    break;
                case 'hidden':
                    $return .= "<input name='$col' placeholder='$col' id='$col' type='hidden' value='$res[$col]'>";
                    break;
                case 'submit':
                    $return .= "<input type='submit' value='Submit'>";
                    break;
                case 'date':
                    $return .= "<div class='gs-span'><label for='$col'>$col</label>
                    <input class='gs-input' name='$col' placeholder='$col' id='$col' type='date' value='$res[$col]'></div>";
                    break;
                default:
                    break;
            }
        }

        if ($form) {
            $return .= "</form>";
        }
        return $return;
    }
}