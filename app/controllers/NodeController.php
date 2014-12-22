<?php

class NodeController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//default cluster function
		$command='info';
		//check if we saved elastic setting in the session
		$params=array();
		if(Session::has('esparams'))
		{
			//Session::flush();
			$params=Session::get('esparams');
		}

		$ESClient = new LaravelElasticSearch($params);


		//make sure we found the elastic search host
		if($found = $ESClient->ping())
		{
			$result = $ESClient->nodes()->$command();
		}
		else
		{
			$result = false;
		}
		//return result is an array

		//result is array, we need convert into tree view data
		$treeData = $ESClient->arrayToJavaTree($result,$command);
		$treeData=substr($treeData, 0, -1); //get rid of the extra comma at the end
		$ArrayToJson = new \Elasticsearch\Serializers\ArrayToJSONSerializer;
		$result = $ArrayToJson->serialize($result); //convert into Jason
		$result = $ESClient->JsonToHtml($result);
		$curComm = $command;
		return View::make('elastics.node', compact('treeData','result','ESClient','curComm'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id='info')
	{
		//check if we saved elastic setting in the session
		$params=array();
		if(Session::has('esparams'))
		{
			//Session::flush();
			$params=Session::get('esparams');
		}

		$ESClient = new LaravelElasticSearch($params);


		//make sure we found the elastic search host
		if($found = $ESClient->ping())
		{
			$result = $ESClient->nodes()->$id();
		}
		else
		{
			$result = false;
		}
		//return result is an array

		//result is array, we need convert into tree view data
		$treeData = $ESClient->arrayToJavaTree($result,$id);
		$treeData=substr($treeData, 0, -1); //get rid of the extra comma at the end
		$ArrayToJson = new \Elasticsearch\Serializers\ArrayToJSONSerializer;
		$result = $ArrayToJson->serialize($result); //convert into Jason
		$result = $ESClient->JsonToHtml($result);
		$curComm = $id;
		return View::make('elastics.node', compact('treeData','result','ESClient','curComm'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function threads()
	{
		$id='hotThreads';
		//check if we saved elastic setting in the session
		$params=array();
		if(Session::has('esparams'))
		{
			//Session::flush();
			$params=Session::get('esparams');
		}

		$ESClient = new LaravelElasticSearch($params);


		//make sure we found the elastic search host
		if($found = $ESClient->ping())
		{
			$result = $ESClient->nodes()->$id();
		}
		else
		{
			$result = false;
		}
		//return result is not an string
		//error_log(print_r($result,true),1,'jgan@cinsay.com');
		$result=explode("\n",$result);

		//result is array, we need convert into tree view data
		$treeData = $ESClient->arrayToJavaTree($result,$id);
		$treeData=substr($treeData, 0, -1); //get rid of the extra comma at the end
		$ArrayToJson = new \Elasticsearch\Serializers\ArrayToJSONSerializer;
		$result = $ArrayToJson->serialize($result); //convert into Jason
		$result = $ESClient->JsonToHtml($result);
		$curComm = $id;
		return View::make('elastics.node', compact('treeData','result','ESClient','curComm'));
	}


}
