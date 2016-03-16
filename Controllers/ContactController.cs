public class ContactController : ApiController
{
    public string[] Get()
    {
            return new string[]
            {
                "Hello",
                "World"
            };
    }
}