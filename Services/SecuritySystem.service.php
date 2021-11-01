<?php 
class SecuritySystemService
{
    public function __construct(
        public object $_context
    ){}
    public static function checkUser(
        string $Username,
        string $Password,
        string|int $UserRights = null // สิทธิการใช้งาน 1 = Admin 2 = Employee 3 = Member เอาหลายสิทธื ใส่ 1,2 ต่อไปเลื่อยๆ
    ) : bool
    {
        try
        {
            $sqlWhereStr = $UserRights != null ? " AND IdUserRights IN($UserRights) " : "";

            $sqlStr = "SELECT Memeber.Id FROM Memeber WHERE 
            Member.UserId != '' AND
            Member.Password != '' AND 
            Member.UserId = '$Username' AND
            Member.Password = '$Password' 
            $sqlWhereStr";

            $info = self::$_context->query($sqlStr)->fetch(2);

            if(!empty($info)) return $info['Id'];

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