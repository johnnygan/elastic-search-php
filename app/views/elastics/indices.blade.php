@extends('layouts.elastics')

@section('main')
<h1>Indices information on the host: {{ link_to_route('elastics.index', $ESClient->hosts,array(),array('title'=>'Back to the Host',
'alt'=>'Back to the host')) }}</h1>

@if ($result === 'false')
  <h3 class="text-warning">Empty Result or Result Contains Error!</h3>
@endif

<p>{{-- link_to_route('users.create', 'Add new user') --}}
@foreach ( $ESClient->indicesFunctions() as $index=>$item )
  {{ link_to_route('indices.show', 'Indices '.$index, array('command'=>strtolower($index)),
  array('class' => (strtolower($index)==$curComm?'btn btn-warning':'btn btn-default'),
  'title'=>$item,
'alt'=>$item)) }}
@endforeach
</p>

<table class="table table-striped table-bordered">
<th>TreeView</th>
<th>Json</th>
<tr><td>
<div id="ElasticInfoTree"></div>
</td><td>{{ $result }}</td></tr>
</table>

<script>
YUI().use(
  'aui-tree-view',
  function(Y) {
    var children = [{{ $treeData }}];

    new Y.TreeView(
      {
        boundingBox: '#ElasticInfoTree',
        children: children
      }
    ).render();
  }
);
</script>

@stop