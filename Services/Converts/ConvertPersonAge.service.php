<?php 
class ConvertPersonAgeService
{
    public function ceBirthDateToCurrenYear(string $valBirthDate,string $type = 'Year') : string
    {
        try
        {
            $birthday = $valBirthDate;      //รูปแบบการเก็บค่าข้อมูลวันเกิด 1999-01-01
            $today = date("Y-m-d");         

            
            list($byear, $bmonth, $bday)= explode("-",$birthday);
            list($tyear, $tmonth, $tday)= explode("-",$today);

            $mbirthday = mktime(0, 0, 0, $bmonth, $bday, $byear); 
            $mnow = mktime(0, 0, 0, $tmonth, $tday, $tyear );
            $mage = ($mnow - $mbirthday);
            
            $u_y=date("Y", $mage)-1970;
            $u_m=date("m",$mage)-1;
            $u_d=date("d",$mage)-1;
            
            if($type == 'Year')
            {
                return $u_y;
            }
            if($type == 'Date')
            {
                return $u_y.'-'.$u_m.'-'.$u_d;
            }
            throw new Exception('The second parameter name type The condition was not found.');
        }
        catch(Exception $e)
        {
            return 'Method ceBirthDateToCurrenYear Error : '.$e->getMessage();
        }
    }
}
?>