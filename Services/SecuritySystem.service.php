<?php 
class SecuritySystemService
{
    public function __construct(
        public object $_context
    ){}
    public static function checkUser(
        string $EnCode64Username,
        string $EnCode64Password,
        string|int $UserRights = null // สิทธิการใช้งาน 1 = Admin 2 = Employee 3 = Member เอาหลายสิทธื ใส่ 1,2 ต่อไปเลื่อยๆ
    ) : object|bool
    {
        try
        {
            $username = base64_decode($EnCode64Username);
            $password = base64_decode($EnCode64Password);
            $sqlWhereStr = $UserRights != null ? " AND IdUserRights IN($UserRights) " : "";

            $sqlStr = "SELECT Memeber.Id FROM Memeber WHERE 
            Member.UserId != '' AND
            Member.Password != '' AND 
            Member.UserId = '$username' AND
            Member.Password = '$password' 
            $sqlWhereStr";

            $info = self::$_context->query($sqlStr)->fetch(5);

            if(!empty($info)) return $info;

            return false;
        }
        catch(Exception $e)
        {
            echo "Method checkUser Error".$e->getMessage();
            exit();
        }
    }
}
?>