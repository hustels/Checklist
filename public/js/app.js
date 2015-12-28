Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
new Vue({
	el: '#checklist',
	data: {
		show: false,
		completedBy: '',
		authenticated_user: '',
		mins: 0,
		idToCheck: '',
		dedicated_time: '',
		hours: '',
		secs: '',
		to_hour: '',
		milis: '',
		messages: [],
		start_time: '',
		end_time: '',
		total_time: '',
		inProgressBy: '',
		totalTasks: [],
		date: new Date(),
		today: '',
		month: '',
		year: '',
		daysOfWeek: ['Domingo' , 'Lunes' , 'Martes' , 'Miércoles' , 'Jueves' , 'Viernes' , 'Sabado' ],
		fullDate: '',
		dayOfMonth: new Date().getDate()
	},
	computed: {
	 
	    completedTasks: function () {
	      // `this` points to the vm instance
	      return this.totalTasks.filter(function(task){
	      	return task.completed;
	      	this.getAllTasks();
	      }).length;
	    },
	    inProgressTasks: function () {
	      // `this` points to the vm instance
	      return this.totalTasks.filter(function(task){
	      	return task.inProgress;
	      	this.getAllTasks();
	      }).length;
	    },
	    remainingTasks: function () {
	      // `this` points to the vm instance
	      return this.totalTasks.filter(function(task){
	      	return ! task.inProgress && ! task.completed;
	      	this.getAllTasks();
	      }).length;
	    }
  	},

	ready: function(){
		this.getAllTasks();
		 //$("#chron1").Chron().start();
		this.sockets();

		

	},
	methods:{
		sockets: function()
		{
		
		var socket = io('http://192.168.0.154:3000');
		    socket.on("test-channel:App\\Events\\taskHasOwned", function(messages){
		         // increase the power everytime we load test route
		         //$('#power').text(parseInt($('#power').text()) + parseInt(message.data.power));
		    
		        // setTimeout(function(){    }, 3000);
		        this.totalTasks = messages.data;
		        //console.log(messages.data);
		       //console.log(this.messages);
		   
		 
		      }.bind(this));
		     },
		// Display all tasks
		getAllTasks: function(){

			// Set dates 
			this.today= this.date.getDay();
			this.month  = this.date.getMonth() +1;
			this.year = this.date.getFullYear();
			this.fullDate = this.daysOfWeek[this.today] + ' ' + this.dayOfMonth +'/' +this.month +'/' + this.year;
			//console.log(this.fullDate);
			this.$http.post('tasks'  , {day: this.today}, function(tasks, status){
				//console.log(tasks);
				this.totalTasks = tasks;
			});
		},
		own: function(id){
			this.start_time = new Date();
			this.sockets();
			//var owner = document.getElementById('auth_user').value;
			
			this.$http.post('ownTask' , {taskId: id , owner: this.authenticated_user , day: this.today} , function(response , status){

				this.getAllTasks();
				if(status === 200){
				this.$http.post('inProgressBy' , {taskId: id , day: this.today} , function(response , status){

				this.idToCheck = response.ownerId;
				if(status ==200){
					this.$http.post('whoOwn' , {idToCheck: this.idToCheck , taskId: id} , function(response , status){
						this.inProgressBy = response.firstname;
						//console.log(this.inProgressBy);

						//console.log(response);
						// listen socket 
					var socket = io('http://192.168.0.154:3000');
					    socket.on("test-channel:App\\Events\\taskInProgressBy", function(messages){
					
					    
					        // setTimeout(function(){    }, 3000);
					        this.totalTasks = messages.data;
					        //console.log(messages);
					        //console.log(messages.data);
					       //console.log(this.messages);
					   
					 
					      }.bind(this));
						//end socket

					});
					this.getAllTasks();

				}

			});
				} // end
			});

		},
		completeTask: function(id){
			
			this.$http.post('isLocked' , {taskId: id } , function(response , status){
				if(response.isLocked === 1 && response.ownerId != this.authenticated_user)
				{
					//alert('Esta bloqueado por otro usuario');
					swal({
						title: '',
						type: "warning",
						 text: "La tarea esta bloqueado por otro operador/@"
						});
					return false;
				}else{
					this.end_time = new Date();
					this.total_time = (this.end_time - this.start_time) / 1000;
					this.secs = Math.floor(this.total_time);
					this.milis = (this.secs) / 60;
					this.mins =  Math.floor(this.milis);
					this.to_hour = (this.mins) /60;
					this.hours = Math.floor(this.to_hour);
					//console.log('Minutos ' + this.mins + 'Segundos ' + this.secs);
					if(this.mins < 10){
						if(this.secs > 59){
							this.secs = 0;
							
						}
					if(this.secs < 10){
					this.dedicated_time = '0' +this.mins + ':' +'0'+  this.secs ;
					}
					if(this.mins > 59){
					this.mins = 0;
					}
					this.dedicated_time ='0' +this.hours+ ':'+'0' +this.mins + ':' + this.secs ;
					console.log(this.dedicated_time);
				
					} // end handling time

					this.$http.post('completeTask' , {taskId: id , day: this.today} , function(response , status){
				//console.log(response);
				this.getAllTasks();
				// begin who compete the task
				if(status === 200){
				swal({
						title: '',
						type: "success",
						 text: "La tarea ha sido completada!!"
						});
				this.$http.post('completedBy' , {taskId: id , day: this.today} , function(response , status){

				this.idToCheck = response.ownerId;
			
				if(status ==200){
					this.$http.post('userCompleteTask' , {idToCheck: this.idToCheck , taskId: id} , function(response , status){
						this.completedBy = response.firstname;
						//console.log(response);

						//console.log(response);
						// listen socket 
					var socket = io('http://192.168.0.154:3000');
					    socket.on("test-channel:App\\Events\\userCompleteTask", function(messages){
					        // setTimeout(function(){    }, 3000);
					        this.totalTasks = messages.data;
					      // console.log( this.totalTasks );
					        //console.log(messages.data);
					       //console.log(this.messages);
					   
					 
					      }.bind(this));
						//end socket

					});
					this.getAllTasks();

					}

				});
				} // end

				//end who complete a task

			});



				}// End check if is locked
			
				
			});


		},
		uncompleteTask: function(id){
			this.$http.post('uncompleteTask' , {taskId: id, owner: this.authenticated_user, day: this.today} , function(response , status){
				//console.log(response);
				this.getAllTasks();

			});
		},
		unOwn: function(id){
			this.$http.post('unOwn' , {taskId: id ,  day: this.today} , function(response , status){
				//console.log(response);

				this.getAllTasks();
				
			});
		}
	}

})