<?php 
/**
 * CodeIgniter Model
 *
 * @category  Model
 * @package   Model
 *
 *
 */

class Data_model extends CI_model
{
	public $result = array();
	public $last_query = false;
	/**
	 * Constructor
	 *
	 * @access public
	 * @return void`
	 */		
	 
	function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * Get Custom Data
	 *
	 * <code>
	 * $this->get();	 
	 * </code>
	 * 
	 * @parma array('table' => ' table_name') - required
	 * @parma array('db' => 'write', 'table' => ' table_name', 'column' => array('col_1','col_2'), 'where'=> array('col1'=>'val1','col2'=>'val2'), 'order' => array('col1'=>'asc','col2'=>'desc'), 'limit' = '10') - optional
	 * 
	 * @access public
	 * @return array
	 */
	 
	public function get($param = false)
	{
		// Validate and Load Database 
		if($param && isset($param['db']) && strtolower($param['db']) == 'write')
		{
			$db = $this->load->database('write', TRUE);
		}
		else
		{
			$db = $this->load->database('read', TRUE);
		}
		
		if($param)
		{
			// Validate Column select field [Optional]
			if(isset($param['column']) && is_array($param['column']) && !empty($param['column']))
			{
				$db->select(str_replace(" , ", " ", implode(", ", $param['column'])));
			}
					
			// Validate where [Optional]
			if(isset($param['where']) && is_array($param['where']) && !empty($param['where']))
			{
				$db->where($param['where']);
			}
			
			if(isset($param['order']))
			{
				if(is_array($param['order']) && !empty($param['order']))
				{
					foreach($param['order'] as $key=>$val)
					{
						$db->order_by($key,$val);
					}
				}
				else if($param['order'] != '')
				{
					$db->order_by($param['order']);
				}
			}

			if(isset($param['like']) && is_array($param['like']) && !empty($param['like']))
			{
				foreach($param['like'] as $key=>$val)
				{
					$db->like($key,$val);
				}
			}
			
			if(isset($param['limit']))
			{
				if(is_array($param['limit']) && count($param['limit']) == 2)
				{
					$db->limit($param['limit'][0],$param['limit'][1]);
				}
				else if(!is_array($param['limit']) && $param['limit'] != '')
				{
					$db->limit($param['limit']);
				}
			}

			if(isset($param['group']) && $param['group'] != '')
			{
				$db->group_by($param['group']);				
			}
		}
		
		$query = $db->get($param['table']);
		$this->last_query = $db->last_query();
		$db->close();
		if($query)
		{
			if($query->num_rows() > 0)
			{
				$this->result = $query->result();
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}		
	}

	public function getData($param = false)
	{
		$this->result = [];
		// Validate and Load Database
		// if($param && isset($param['db']) && strtolower($param['db']) == 'write')
		// {
		// 	$db = $this->load->database('write', TRUE);
		// }
		// else
		// {
		// 	$db = $this->load->database('read', TRUE);
		// }
		$db = $this->load->database('write', TRUE);
		
		if($param)
		{
			// Validate Column select field [Optional]
			if(isset($param['column']) && is_array($param['column']) && !empty($param['column']))
			{
				$db->select(str_replace(" , ", " ", implode(", ", $param['column'])));
			}

			// Validate where [Optional]
			if(isset($param['where']) && is_array($param['where']) && !empty($param['where']))
			{
				$db->where($param['where']);
			}

			if(isset($param['order']) && is_array($param['order']) && !empty($param['order']))
			{
				foreach($param['order'] as $key=>$val)
				{
					$db->order_by($key,$val);
				}
			}

			if(isset($param['limit']) && $param['limit'] != '')
			{
				$db->limit($param['limit']);
			}
		}

		$query = $db->get($param['table']);
		$this->last_query = $db->last_query();

		$db->close();
		if($query)
		{
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Insert Record
	 *
	 * <code>
	 * $this->insert();	 
	 * </code>
	 * 
	 * @parma array('table' => ' table_name') - required
	 * @parma array('data'=> array('col1'=>'val1','col2'=>'val2'))
	 * 
	 * @access public
	 * @return array
	 */
	
	public function insert($param = false)
	{
		$db = $this->load->database('write', TRUE);		
		if(!isset($param['table']) || $param['table'] == '')
		{
			$db->close();
			return false;
		}
		
		if(!isset($param['data']) || !is_array($param['data']) || empty($param['data']))
		{
			$db->close();
			return false;
		}
		
		$query = $db->insert($param['table'],$param['data']);
		$this->last_query = $db->last_query();
		
		if($db->affected_rows() > 0)
		{
			$id = $db->insert_id();
			$db->close();
			return $id;
		}
		else
		{
			$db->close();
			return false;
		}	
	}	

	public function insert_ignore($param = [])
	{
		if(!isset($param['table']) || $param['table'] == '')
		{
			return false;
		}
		
		if(!isset($param['data']) || !is_array($param['data']) || empty($param['data']))
		{
			return false;
		}
		
		$set = [];
		foreach($param['data'] AS $key => $val)
		{
			if($val === NULL || $val == null)
			{
				$set[] = " ".$key." ";
			}
			else
			{
				if(!is_string($val) && is_numeric($val))
				{
					$set[] = " ".$key." = " . $val ." ";
				}
				else
				{
					$set[] = " ".$key." = '" . $val ."' ";
				}
			}
		}

		$db = $this->load->database('write', TRUE);	
		$query = "INSERT IGNORE INTO ".$param['table']." SET " . implode(",",$set);

		$qry = $db->query($query);
		$this->last_query = $db->last_query();
		
		if($db->affected_rows() > 0)
		{
			$id = $db->insert_id();
			$db->close();
			return $id;
		}
		else
		{
			$db->close();
			return false;
		}	
	}

	public function insert_batch_ignore($param = [])
	{
		if(!isset($param['table']) || $param['table'] == '')
		{
			return false;
		}
		
		if(!isset($param['data']) || !is_array($param['data']) || empty($param['data']))
		{
			return false;
		}

		$db = $this->load->database('write', TRUE);	

		$cols = '';
		$vals = '';
		$vals_r = [];
		foreach($param['data'] AS $record)
		{
			$c = [];
			$r = [];
			foreach($record AS $key => $val)
			{
				$c[] = $key;
				$r[] = $val;	
			}
			$cols = "(".implode(",",$c).")";
			$vals_r[] = "(".implode(",",$db->escape($r)).")";
		}

		$vals = implode(",",$vals_r);

		$query = "INSERT IGNORE INTO ".$param['table']." ".$cols." VALUES ".$vals;

		$qry = $db->query($query);
		$this->last_query = $db->last_query();
		$count = $db->affected_rows();
		if($count > 0)
		{
			$db->close();
			return $count;
		}
		else
		{
			$db->close();
			return false;
		}	
	}

	public function insert_batch($p)
	{
		$db = $this->load->database('write',true);
		$qry = $db->insert_batch($p['table'],$p['data']);
		$this->last_query = $db->last_query();

		if($db->affected_rows() > 0)
		{
			$cnt = $db->affected_rows();
			$db->close();
			return $cnt;
		}
		else
		{
			$db->close();
			return false;
		}		
	}
	
	//where will not bean array it has to be a key
	public function update_batch($p)
	{
		$db = $this->load->database('write',true);
		$qry = $db->update_batch($p['table'], $p['data'], $p['where']); 
		$this->last_query = $db->last_query();

		if($db->affected_rows() > 0)
		{
			$cnt = $db->affected_rows();
			$db->close();
			return $cnt;
		}
		else
		{
			$db->close();
			return false;
		}		
	}
	
	/**
	 * Update Record
	 *
	 * <code>
	 * $this->update();	 
	 * </code>
	 * 
	 * @parma array('table' => ' table_name') - required
	 * @parma array('data'=> array('col1'=>'val1','col2'=>'val2'),'where' => array('col3'=>'val3','col4'=>'val4'))
	 * 
	 * @access public
	 * @return array
	 */
	
	public function update($param = false)
	{
		$db = $this->load->database('write', TRUE);		
		if(!isset($param['table']) || $param['table'] == '')
		{
			$db->close();
			return false;
		}
		
		if(!isset($param['data']) || !is_array($param['data']) || empty($param['data']))
		{
			$db->close();
			return false;
		}
		
		if(!isset($param['where']) || !is_array($param['where']) || empty($param['where']))
		{
			$db->close();
			return false;
		}
		if(isset($param['order']))
		{
			if(is_array($param['order']) && !empty($param['order']))
			{
				foreach($param['order'] as $key=>$val)
				{
					$db->order_by($key,$val);
				}
			}
			else if($param['order'] != '')
			{
				$db->order_by($param['order']);
			}
		}

		if(isset($param['limit']))
		{
			if(is_array($param['limit']) && count($param['limit']) == 2)
			{
				$db->limit($param['limit'][0],$param['limit'][1]);
			}
			else if(!is_array($param['limit']) && $param['limit'] != '')
			{
				$db->limit($param['limit']);
			}
		}
		
		$db->update($param['table'],$param['data'],$param['where']);
		$this->last_query = $db->last_query();
		
		if($db->affected_rows() > 0)
		{
			$db->close();
			return true;
		}
		else
		{
			$db->close();
			return false;
		}	
	}
	
	/**
	 * Delete Record
	 *
	 * <code>
	 * $this->delete();	 
	 * </code>
	 * 
	 * @parma array('table' => ' table_name') - required
	 * @parma array('where' => array('col3'=>'val3','col4'=>'val4'))
	 * 
	 * @access public
	 * @return array
	 */
	
	public function delete($param = false)
	{
		$db = $this->load->database('write', TRUE);		
		if(!isset($param['table']) || $param['table'] == '')
		{
			$db->close();
			return false;
		}
		
		if(!isset($param['where']) || !is_array($param['where']) || empty($param['where']))
		{
			$db->close();
			return false;
		}
		
		$db->delete($param['table'],$param['where']);
		
		$this->last_query = $db->last_query();
		if($db->affected_rows() > 0)
		{
			$db->close();
			return true;
		}
		else
		{
			$db->close();
			return false;
		}	
	}	

	public function query(array $p = [])
	{
		$adb = 'write';
		if(array_key_exists('db', $p))
		{
			$adb = $p['db'];
		}

		if(!array_key_exists('data', $p))
		{
			$p['data'] = [];
		}

		$db = $this->load->database($adb,true);
		
		$query = $db->query($p['query'],$p['data']);

		$this->last_query = $db->last_query();
		
		if(method_exists($db,'affected_rows'))
		{
			$this->affected_rows = $db->affected_rows();
		}

		$insert_id = 0;
		if(method_exists($db,'insert_id'))
		{
			$insert_id = $db->insert_id();
		}

		$db->close();

		$num_rows = 0;
		$row = 0;
		if(method_exists($query,'num_rows'))
		{
			$num_rows = $query->num_rows();
			if($num_rows > 0)
			{
				if(method_exists($query,'result'))
				{
					$this->result = $query->result();
				}

				if(method_exists($query,'row'))
				{
					$row = $query->row();
				}
			}
		}

		if($num_rows)
		{
			return $row;
		}
		else if($insert_id > 0)
		{
			return $insert_id;
		}
		else if($this->affected_rows > 0)
		{
			return $this->affected_rows;
		}
		else
		{
			return false;
		}
	}

	public function addColumn($p)
	{
		$db = $this->load->database('write',true);
		$qry = $db->query($p['query'],$p['data']);

		$this->last_query = $db->last_query();

		if($qry)
		{	
			$this->result = $qry;
			$db->close();
			return $qry;
		}
		else
		{
			$db->close();
			return false;
		}
	}

	public function updateOnExist(array $data=[], array $where=[] ,$table = ''){
		try{

			$this->result = [];
			$db = $this->load->database('write',TRUE);

			$db->where($where);

			$q = $db->get($table);
			$this->last_query = $db->last_query();
			
			if ( $q->num_rows() > 0 ) 
			{
				$db->where($where);
				$db->update($table,$data);
				$res = $db->affected_rows() || 0;
			} else {
				$db->insert($table,$data);
				$res = $db->insert_id() || 0;
			}

			$db->close();
			
			return $res;
		}catch(Exception $e){
			//echo $e->getMessage(); die;
		}
	}
}

/* End of file data_model.php */
/* Location: ./application/models/data_model.php */