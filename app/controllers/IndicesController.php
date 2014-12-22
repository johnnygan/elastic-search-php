<?php

class IndicesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//default cluster function
		$command='stats';
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
			$result = $ESClient->indices()->$command();

			/*
			$search['index'] = 'test-index';
			$search['type']  = 'test-data';
			//$search['body']['query']['match']['email'] = 'hattiebond@netagy.com';
			$search['body']['query']['match_all']=[];
			//$search['body']['size']=1;

			$result = $ESClient->search($search);
			$indicesResult = $ESClient->findAllIndex();
			*/
		}
		else
		{
			$result = false;
		}
		//return result is an array

		//result is array, we need convert into tree view data
		$treeData = $ESClient->arrayToJavaTree($result,$command);
		$treeData=substr($treeData, 0, -1); //get rid of the extra comma at the end
		//$indices = $ESClient->arrayToJavaTree($indicesResult,'Index');
		//$indices = substr($indices, 0, -1); //get rid of the extra comma at the end
		$ArrayToJson = new \Elasticsearch\Serializers\ArrayToJSONSerializer;
		$result = $ArrayToJson->serialize($result); //convert into Jason
		$result = $ESClient->JsonToHtml($result);
		$curComm = $command;
		return View::make('elastics.indices', compact('treeData','result','ESClient','curComm'));
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
	public function show($id='stats')
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
			$result = $ESClient->indices()->$id();
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
		return View::make('elastics.indices', compact('treeData','result','ESClient','curComm'));
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


}
