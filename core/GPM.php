<?php
namespace Core;

trait GPM {

    protected function getWidgetsBuffer(): array {
        $buffer = array();
        $sel = array();
   		$query='SELECT * FROM cubos ORDER BY valuability DESC';
   		$sel=$this->dbm->fa($query);
   		$count = count($this->dbm->fa($query));
           // Create buffer for output
   		$params['statuses']=[0=>'archived',1=>'deprecated',2=>'pending',3=>'active'];
           $buffer['count'] = $count;
           $buffer['list'] = $sel;
           $buffer['html'] = include_buffer(ADMIN_ROOT."main/cubos/cubos_buffer.php", $sel,$params);
           return $buffer;
       }

    /*
    create new cubo routine
    */
protected function createNewCubo (string $name, string $description, string $ideally): bool|int{
        //create folder and public.php
          $cuboDir = CUBOS_ROOT . $name . '/';

           // Create the folder for the new cubo
           if (!mkdir($cuboDir, 0777, true)) {
               // Return false if the directory could not be created
               return false;
           }
          // Define the path for the public.php file
            $filePath = $cuboDir . 'public.php';

            // Initialize the content to write into public.php
            $content = "<?php\n";
            $content .= "// Auto-generated public.php file for cubo: $name\n\n";
            $content .= "echo 'Welcome to the $name cubo!';\n";
            $content .= "\n";
            $content .= "// Additional content\n";
            $content .= "echo 'John Smith';\n";

            // Write the content to public.php
            if (file_put_contents($filePath, $content) === false) {
                // Return false if the file could not be created or written
                return false;
            }
            // Prepare the data for the database insertion
            $data = [
                'system_id' => 1,
                'name' => $name,
                'description' => $description,
                'created' => date('Y-m-d H:i:s')
            ];
            // Insert the data into the 'cubos' table
            $insert = $this->dbm->inse("cubos", $data);
            // Return the result of the database insertion
            return $insert;
        }

    // Retrieve all cubos
    protected function getWidgets(): array {
        return $this->dbm->fa('SELECT * FROM cubos');        
    }
    // Retrieve  cubos buffer

    // Retrieve a single cubos by ID
    protected function getWidget(int $id): array {
        return $this->dbm->f('SELECT * FROM cubos WHERE id = ? ORDER BY valuability DESC',[$id]);        
    }

    // Retrieve cubos logs
    protected function getWidgetLogs(int $widgetId): array {
        return $this->dbm->fa('SELECT * FROM cubo_logs WHERE widget_id =? ',[$widgetId]);        
    }
    // Retrieve cubos logs
    protected function test(): array {
	return ["my"=>'love'];
	}
    protected function getSystemLogsBuffer(): ?array {
       $buffer = array();
        $sel = array(); 
		$query='SELECT systems.*,system_logs.* FROM systems left join system_logs ON systems.id=system_logs.system_id ';
		$selsystems=$this->dbm->fa($query);  
			for($i=0;$i<count($selsystems);$i++) { 
				$sel[$selsystems[$i]["system_id"]][]=$selsystems[$i];
			}      
        // Create buffer for output		
        $buffer['count'] = count($selsystems);
        $buffer['list'] = $sel;
        $buffer['html'] = include_buffer(ADMIN_ROOT."main/gpm/system_buffer.php", $sel);
        return $buffer;
    }

    // Add a new widget log entry
    protected function addWidgetLog(int $widgetId, string $action, string $summary): int {
        return $this->dbm->inse('cubo_logs',['widget_id'=>$widgetId, 'action'=>$action, 'summary'=>$summary]);        
    }

    // Update a widget
    protected function updateWidget(int $id, array $data): bool {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $sql = 'UPDATE cubos SET ' . implode(', ', $fields) . ' WHERE id = ?';
        return $this->dbm->q($sql,[$id]);        
    }

    // Add a new widget
    protected function addWidget(array $data): bool {
        $keys = array_keys($data);
        $columns = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);
        $sql = "INSERT INTO cubos ($columns) VALUES ($placeholders)";
        return $this->dbm->q($sql);        
    }
// metric.php sub 
   protected function addMetric(): ?array {
		$sql = "SELECT s.name, DATE_FORMAT(sl.created, '%Y-%m-%d') AS week, sl.progress_level
        FROM system_logs sl JOIN systems s ON sl.system_id = s.id
        WHERE sl.created BETWEEN '2024-07-05' AND '2024-09-08'
        ORDER BY sl.created";
		$res = $this->dbm->fa($sql);
		$data = array();
		if (count($res) > 0) {
			for($i=0;$i<count($res);$i++){
			$data[$res[$i]["name"]][] = array("week" => $res[$i]["week"], "progress" => $res[$i]["progress_level"]);
		}}
	    return $data;
	}	

    // Delete a widget
    protected function deleteWidget(int $id): bool {
        return $this->dbm->q('DELETE FROM cubos WHERE id =?',[$id]);        
    }

	protected function func($b,$c){
		//b:method c:param
		$cq = $b == 'fetchList1' ? explode(',', $c) : $c;
		if(in_array($b,array("name_not_exist","validate"))){
			$sel = $this->dbm->$b($cq);
			return $sel ? json_encode("OK"): json_encode("NO");
		}else{
			$sel = $this->dbm->$b($cq);
			return $b=="q" && $sel ? json_encode("OK") : json_encode($sel);
		}
	}
}
?>
