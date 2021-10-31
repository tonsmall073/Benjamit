<?php
class LoginRequestModel
{
    public string $Username;
    public string $Password;
}
class LoginResponseModel
{
    public object $Content;
    public function __construct()
    {
        $this->Content = new ContentList();
    }
    public string $Status;
    public string $MessageDesc;
}
class ContentList
{
    //public string $Id;
    public string $User;
    public string $Pass;
    public string $FullName;
    public string $NickName;
}
?>