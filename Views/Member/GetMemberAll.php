<script>
async function sendApiGetMember()
{
    let xmlHttp = new XMLHttpRequest();
    let url = '../../Services/Member/Member.controller.php';

    xmlHttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            const myArr = JSON.parse(this.responseText);
        }
    };
    xmlHttp.open("POST", url, true);
    xmlHttp.send();
}
sendApiGetMember();
</script>