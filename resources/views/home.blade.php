@extends('layouts.app')

@section('content')
<div class="container spark-screen"  id="checklist">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><p class="lead">Tareas -- @{{fullDate}} </p></div>
	
            </div>
        </div>
    </div>
      <!-- Row begin -->
      <input type="hidden" v-model="authenticated_user" value="{{Auth::user()->id}}">
      <div class="row">
      	<div class="col-md-4">
      	
		<h4 v-show="remainingTasks">Tareas pendientes ( @{{remainingTasks}})</h4>
		<table id="remainingTasks" class="table table-striped" v-for="task in totalTasks">
			<p v-show="!remainingTasks"  class="lead">No hay tareas pendientes</p>
			<tr  v-show="task.completed ===0 && task.inProgress === 0" id="colorRemainingTasks" >
				<td class="tableTitle">@{{ task.title }}</td>
				<td class="tableTitle">@{{ task.start_time}}</td>
				<td class="tableTitle">@{{ task.end_time}}</td>
			</tr>
			<tr v-show="task.completed === 0 && task.inProgress === 0">
				<td colspan="3"  ><p>@{{ task.description }} <br/>
				<button class="bnt btn-success"  @click="own(task.id) ">Poseer</button>
				</p></td>

			</tr>
		</table>
	</div>
	<div class="col-md-4">
		<h4 v-show="inProgressTasks">Tareas en progreso  (@{{inProgressTasks}})</h4>
		<table id="" class="table table-striped" v-for="task in totalTasks ">
			<tr  v-show="task.inProgress === 1 && task.completed === 0"  id="colorInprogressTasks">
				 <td class="tableTitle">@{{ task.title }}</td>
				<td class="tableTitle">@{{ task.start_time}}</td>
				<td class="tableTitle">@{{ task.end_time}}</td>
				<td><i class="fa fa-refresh fa-spin" id="inProgress"></i></td>
				<td class="tableTitle">@{{task.inProgressBy}}</td>
				<p v-show="!inProgressTasks" class="lead">No hay tareas en progreso</p>
			</tr>
			<tr v-show="task.inProgress === 1  && task.completed === 0 " >
				 <td colspan="3"  ><p>@{{ task.description }} <br/>
				 <button class="bnt btn-success"  @click="completeTask(task.id)">Completar</button> 
				 <button class="bnt btn-default"  @click="unOwn(task.id)">Desposeer</button>
				
				 </p>
				 </td>
				
			</tr>
		</table>
	</div>
	<div class="col-md-4">
		<h4 v-show="completedTasks">Tareas completadas  (@{{completedTasks}})</h4>
		<table id="completedTasks" class="table table-striped" v-for="task in totalTasks">
			<tr  v-show="task.completed === 1"  id="colorCompletedTasks">
				 <td class="tableTitle">@{{ task.title }}</td>
				 <td class="tableTitle">@{{ task.start_time}}</td>
				 <td class="tableTitle">@{{ task.end_time}}</td>
				 <td class="tableTitle">@{{task.completedBy}}</td>
				 <td><i class="fa fa-check" id="completed"></i></td>
				 <p v-show="!completedTasks" class="lead">No hay tareas completadas</p>
			</tr>
			<tr v-show="task.completed ===1">
				<td colspan="3">
					<p >@{{ task.description }} </p></br>
					<button class="bnt btn-success" @click="uncompleteTask(task.id)"> 
						Retomar
					</button>
				</td>
				<!-- <td>@{{dedicated_time}}</td> -->
			</tr>

		</table>
	</div>
      </div>
      <!-- End row -->

   
</div>
@endsection
