<?php

namespace App\Http\Controllers;
use App\Events\taskHasOwned;
use App\Events\taskInProgressBy;
use App\Events\userCompleteTask;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Task;
use App\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
      
        $tasks = Task::all();
        return view('home');
    }
    public function home()
    {
         return view('welcome');
        //return redirect()->route('home');
    }
    // Get today tasks
    public function showTodayTasks(Request $request)
    {
        $day = $request->input('day');
       $tasks = Task::where('day' , $day)->get();
       return $tasks;
        //return response()->json(Task::all());
    }

    // Own a task
    public function ownTask(Request $request)
    {
       // return event(new taskHasOwned('Cheikh Ndiaye'));
        $taskId = $request->input('taskId');
        $taskInprogress = Task::find($taskId);
        if($taskInprogress->inProgress ===1){
            $messages = ['message' => 'isInProgress'];
            //return  $messages;
            //return 'already';
        }else{
        $owner= $request->input('owner');
        $task = Task::find($taskId);
         $task->ownerId = $owner;
          $task->completedBy= '';
        $task->inProgress = true;
         $task->isLocked = true;
       if( $task->save()){
        $theOwner = Task::find($owner);
        $day = $request->input('day');
       $tasks = Task::where('day' , $day)->get();
        //return User::where('id' , $theOwner->id)->get() ;
        return event(new taskHasOwned($tasks));
       }else{
        return 'Could not be updated';
       }


   }
    }
    //Complete task
    public function completeTask(Request $request)
    {
        $taskId = $request->input('taskId');
        $task = Task::find($taskId);
        $task->completed = true;
         $task->inProgress = false;
          $task->isLocked = false;
         $task->inProgressBy = '';
       if( $task->save()){
          $day = $request->input('day');
       $tasks = Task::where('day' , $day)->get();
        //return User::where('id' , $theOwner->id)->get() ;
        return event(new taskHasOwned($tasks ));
       }else{
        return 'Could not be completed';
       }
    }
      //Unown a task
    public function unOwnTask(Request $request)
    {
        //$owner= $request->input('owner');
        $taskId = $request->input('taskId');
        $task = Task::find($taskId);
        $task->completed = false;
       // $task->ownerId = 5;
        $task->completedBy = '';
         $task->isLocked = false;
        $task->inProgress = false;
       if( $task->save()){
          $day = $request->input('day');
       $tasks = Task::where('day' , $day)->get();
        //return User::where('id' , $theOwner->id)->get() ;
        return event(new taskHasOwned($tasks));
       }else{
        return 'Could not be unowned';
       }
    }
    
    // uncompleteTask
    public function uncompleteTask(Request $request)
    {
        $taskId = $request->input('taskId');
        $owner= $request->input('owner');
        $task = Task::find($taskId);
        $task->completed = false;
        $task->inProgress = true;
        $task->ownerId = $owner;
         $task->completedBy = '';
       if( $task->save()){
          $day = $request->input('day');
       $tasks = Task::where('day' , $day)->get();
        //return User::where('id' , $theOwner->id)->get() ;
        return event(new taskHasOwned($tasks));
       }else{
        return 'Could not be uncompleted';
       }
    }

        // In progress by
    public function inProgressBy(Request $request)
    {
       // return event(new taskHasOwned('Cheikh Ndiaye'));
        $taskId = $request->input('taskId');
        $owner = $request->input('owner');
        $taskInprogress = Task::find($taskId);

       // $inProgressBy = User::find( $taskId);
        return Task::find($taskId);
 
    }
    //Who own the task
    public function whoOwn(Request $request)
    {
       // return event(new taskHasOwned('Cheikh Ndiaye'));
        $idToCheck = $request->input('idToCheck');
        $taskId =  $request->input('taskId');
        $task = Task::find($taskId);
        $user = User::find($idToCheck);
        $task->inProgressBy = $user->firstname . '  ' . $user->lastname;
        $task->save();
        $result= User::find($idToCheck);
        $tasks = Task::all();
        return event(new taskInProgressBy($tasks));

 
    }


    // completed by
       public function completedBy(Request $request)
    {
    
        $taskId = $request->input('taskId');
        $owner = $request->input('owner');
        $taskInprogress = Task::find($taskId);

        return Task::find($taskId);
 
    }

    // A user complete a task
     public function userCompleteTask(Request $request)
    {
       
        $idToCheck = $request->input('idToCheck');
        $taskId =  $request->input('taskId');
        $task = Task::find($taskId);
        $user = User::find($idToCheck);
        $task->completedBy = $user->firstname . '  ' . $user->lastname;
        $task->save();
        $result= User::find($idToCheck);
        $tasks = Task::all();
        return event(new userCompleteTask($tasks));
        //return $tasks;

 
    }

    // Check if a task is locked
        // completed by
       public function isLocked(Request $request)
    {
    
        $taskId = $request->input('taskId');
      
        $isLocked = Task::find($taskId);

        return $isLocked;
 
    }

}
