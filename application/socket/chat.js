var http = require('http');
var io = require('socket.io');

//开启服务器
var server = http.createServer((req, res) => {});
//监听端口
server.listen(2183);
//用户储存在线用户
var userNode = [];
//储存所有信息
var msgArr = [];

//监听服务器，监听用户链接
io.listen(server).on('connection', (user) => {
	
	//从前台接受输入的名字，储存用户名字，向其他用户发送更新的用户列表
	user.on('name', (str) => {
		//新登陆
		if(userNode.indexOf(str) == -1) {
			//存入在线用户
			userNode.push(str);
//			console.log("上线");
//			console.log(userNode);
			user.emit('repeat', 'no');
			//储存该用户的名字
			user.lastNames = str;
			//用户判断是否重复登陆
			user.repeatlogin = "no";
			//向其他用户发送更新的用户列表
			user.broadcast.emit('user', userNode);
			//向自己发送更新的用户列表		
			user.emit('user', userNode);
			//向其他用户发送登陆的该用户
			user.broadcast.emit('userin', str);
			//向自己发送登陆的该用户	
			user.emit('userin', str);
			//向自己发送之前的所有消息
			user.emit('allmsg', msgArr);
		} else {
			user.emit('repeat', 'yes');
			user.repeatlogin = "yes";
		}

	});

	//从前台接受消息，向自己、其他用户发送该用户的消息
	user.on('msg', (str) => {
		var date = new Date();
		if(userNode.indexOf(user.lastNames)!==-1){
			//储存所有信息
			msgArr.push({
				userName: user.lastNames,
				msg: str,
				timer: date.getMonth()+1+"/"+date.getDate()+" "+date.toLocaleTimeString()
			});
			
			//向其他用户发送该用户的消息
			user.broadcast.emit('msg', {
				userName: user.lastNames,
				msg: str,
				timer: date.getMonth()+1+"/"+date.getDate()+" "+date.toLocaleTimeString()
			});
			
			//向自己发送该用户的消息
			user.emit('mymsg', {
				userName: user.lastNames,
				msg: str,
				timer: date.getMonth()+1+"/"+date.getDate()+" "+date.toLocaleTimeString()
			});
		}else{
			user.emit('myup', "out");	
		}
		
	});

	//退出登陆
	user.on('disconnect', () => {
		//由于重复登陆而退出，不清除改用户的在线，否则清除用户的在线。
		if(user.repeatlogin=="yes") {
			user.on('repeatback', (str) => {
				if(str=='yes'){
					user.repeatlogin="no";
				}			
			})
		}else{
			//非重复登陆退出
			if(userNode.indexOf(user.lastNames)!==-1){
				user.broadcast.emit('userup', user.lastNames);			
				userNode.splice(userNode.indexOf(user.lastNames), 1);
			}		
		}
		
//		console.log("下线");
//		console.log(userNode);
	});
})