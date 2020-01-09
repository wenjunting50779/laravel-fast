<?php

/**
 * websocket接口，要使用websokect必须实现此接口
 */
namespace LaravelFast;

interface WebSocketInterface
{
    /**
     * websocket 连接成功事件回调
     * @param $server
     * @param $request
     *
     * @return mixed
     */
    public function onOpen($server,$request);

    /**
     * websocket 发送消息事件回调
     * @param $server
     * @param $frame
     *
     * @return mixed
     */
    public function onMessage($server,$frame);

    /**
     * websocket 关闭连接事件回调
     * @param $server
     * @param $fd
     *
     * @return mixed
     */
    public function onClose($server,$fd);

}
