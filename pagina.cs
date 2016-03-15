using System;
using System.IO;
using System.Web.UI;

class Program
{
    static void Main()
    {
	using (StringWriter stringWriter = new StringWriter())
	using (HtmlTextWriter htmlWriter = new HtmlTextWriter(stringWriter))
	{
	    htmlWriter.RenderBeginTag(HtmlTextWriterTag.Span);
	    htmlWriter.Write("Perls");
	    htmlWriter.RenderEndTag();
	    Console.WriteLine(stringWriter);
	}
    }
}
