<?php 
class SecuritySystemService
{
    public static function checkUser(
        object $context, 
        string $EnCode64Username,
        string $EnCode64Password,
        string|int $UserRights = null // สิทธิการใช้งาน 1 = Admin 2 = Employee 3 = Member เอาหลายสิทธื ใส่ 1,2 ต่อไปเลื่อยๆ
    ) : object|bool
    {
        try
        {
            $username = base64_decode($EnCode64Username);
            $password = base64_decode($EnCode64Password);
            $sqlWhereStr = $UserRights != null ? " AND Member.IdUserRights IN($UserRights) " : "";

            $sqlStr = "SELECT Member.Id FROM Member WHERE 
            Member.UserId != '' AND
            Member.Password != '' AND 
            Member.UserId = '$username' AND
            Member.Password = '$password' 
            $sqlWhereStr";

            $info = $context->query($sqlStr)->fetch(5);

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