<?php

namespace System\Http;

use System\Application;

class Response
{
    /**
     * Application Object 
     * 
     * @var \System\Application
     */
    private $app;

    /**
     * Headers container that will be send to browser 
     * 
     * @var array
     */
    private $headers = [];

    /**
     * the content that will be send browser 
     * 
     * @var string 
     */
    private $content = '';


    /**
     * Constructor 
     * 
     * @param \System\Application
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Set the response output content 
     * 
     * @param string $content 
     * 
     * @return void
     */
    public function setOutput($content)
    {
        $this->content = $content;
    }

    /**
     * Send the response headers
     * 
     * @param string $header
     * @param mixed value
     * 
     * @return void
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }

    /**
     * Send the response headers and content 
     * 
     * @return void
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendOutput();
    }

    /**
     * send the response headers 
     * 
     * @return void
     */
    private function sendHeaders()
    {
        foreach ($this->headers as $header => $value) {
            header($header . ':' . $value);
        }
    }

    /**
     * send the response output
     * 
     * @return void
     */
    private function sendOutput()
    {
        echo $this->content;
    }
}
