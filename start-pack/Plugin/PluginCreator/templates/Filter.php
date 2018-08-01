<?php


namespace Plugin\#NAME#;


class Filter
{
    /**
     * @param \Ip\Response $response
     * @return mixed
     */
    public static function ipSendResponse($response)
    {
        // modify response before sending
        return $response;
    }
}
