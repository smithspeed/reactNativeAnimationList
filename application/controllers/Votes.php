<?php 

/**
 * Bots Class
 */
class Votes extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index(){

		$data = new stdClass();

		$data->template = ['datatable','alert'];

		$this->load->view('votes/votes_index.php',$data);
	}

	public function getList(){

		$this->data->result = [];
        $this->data->query([
            'query' => "
                select 
                    cl.name as coinName,
                    cl.id as coinId,
                    tvl.totalCount as totalVotes
                from coin_list as cl 
                left join 
                    (select count(*) as totalCount , coin_id as totalCoinId from vote_list where is_active='1' group by coin_id) as tvl 
	            on cl.id = tvl.totalCoinId
                order by tvl.totalCount desc
            "
        ]);
        $totalRes = $this->data->result;

		response('TXN','',$totalRes);
	}

	public function storeVotes(){

		$coinId = $_POST['coinId'];

		if($coinId==''){

			response('ERR','Coin Id is required');
		}

		$numberVotes = (int)$_POST['numberVotes'];

		if($numberVotes==''){

			response('ERR','Number of votes is required');
		}

        $incVotes = [];

        for($i=0 ; $i < $numberVotes; $i++){

            $obj = new stdClass();

            $obj->coin_id = $coinId;

            $obj->ip_address = '127.0.0.1';

            $obj->is_active = '1';

            $incVotes[] = $obj;
        }

        if(count($incVotes)==0){

            response('TXN','No Votes to Increase');
        }

        $insBot = $this->data->insert_batch([
            'db' => 'write',
            'table' => 'vote_list',
            'data' => $incVotes
        ]);

        if(!$insBot){

            response('ERR','Failed to Increase number of votes');
        }

        response('TXN','Votes Increase Successfully');
		
	}

	public function removeVotes(){

		$numberVotes = $_POST['numberVotes'];

		if($numberVotes==''){

			response('ERR','Number of Votes is required');
		}

        $coinId = $_POST['coinId'];

		if($coinId==''){

			response('ERR','Coin Id is required');
		}

        $isRemove = $this->data->query([
            'query' => "
                delete 
                    from 
                vote_list 
                where id IN (
                    select id 
                        from 
                        (select id from vote_list where coin_id = '$coinId' order by id asc limit $numberVotes) t
                    )
            "
        ]);
        
        if(!$isRemove){
            response('ERR','Failed to remove votes');
        }

		response('TXN','Votes Removed Successfully',);
	}

	public function clearVotes(){

		$coinId = $_POST['coinId'];

		if($coinId==''){

			response('ERR','Coin Id is required');
		}

		$delVotes = $this->data->delete([
			'db' => 'write',
			'table' => 'vote_list',
			'where' => [
				'coin_id' => $coinId
			]
		]);

		if(!$delVotes){
			response('ERR','Failed to Clear Votes');
		}

		response('TXN','Votes Cleared Successfully');
	}

}



?>