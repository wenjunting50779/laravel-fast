<?php

namespace LaravelFast\Example;

use LaravelFast\WebSocketInterface;

class WebSocket implements WebSocketInterface
{

    public function onOpen($server, $request)
    {
        try {
            $message = "server: handshake success with fd{$request->fd}\n";
            $server->push($request->fd, $message);
        } catch (\Exception $e) {
            echo "send failed";
        }
    }

    public function onMessage($server, $frame)
    {
        //取出关联的用户ID和接收者的fd
        $message = "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}==".date('Y-m-d H:i:s')."\n";
        //发送结果返回
        $server->push($frame->fd, $message);

    }

    public function onClose($server, $fd)
    {
        echo "client {$fd} closed\n";
    }
}