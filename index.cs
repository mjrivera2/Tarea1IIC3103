<%@ Page Language="C#" %>
<script runat=server>
protected String GetTime()
{
    return DateTime.Now.ToString("t");
}
</script>
<html>
<body>
    <form id="form1" runat="server">
       Current server time is <% =GetTime()%>.
    </form>
</body>
</html>