<?php 
class ReturnObject
{
    public $status;
    public $message;
    public function __construct(int $status, string $message)
    {
        $this->message = $message;
        $this->status = $status;
    }
}
