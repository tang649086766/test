<?php

$exchangeName = 'demo1';   //交换器
$queueName = 'task_queue';  //队列名称
$routeKey = 'task_queue';  //节点
$message = empty($argv[1]) ? 'Hello World!' : ''.$argv[1];    //信息


//建立连接
$connection = new AMQPConnection(array('host'=>'127.0.0.1', 'port'=>'5672', 'vhost'=>'/', 'login'=>'guest', 'password'=>'guest'));
$connection->connect() or dir("cannot connect to the broker!\n");


try{
	//创建连接
	$channel = new AMQPChannel($connection);
	//创建交换机
	$exchange = new AMQPExchange($channel);
	$exchange->setName($exchangeName);
	$exchange->declareExchange();

	//创建队列
	$queue = new AMQPQueue($channel);
	$queue->setName($queueName);
	//设置队列持久化, AMQP_EXCLUSIVE：断开连接自动销毁
	$queue->setFlags(AMQP_DURABLE);
	$queue->declare();

	//发送消息
	$exchange->publish($message, $routeKey);
	var_dump("[x] Sent $message");

} catch (AMQPConnectionException $e) {
	exit($e);
}

//关闭连接
$connection->disconnect();