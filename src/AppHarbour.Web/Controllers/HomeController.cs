﻿using System.Web.Mvc;

namespace AppHarbour.Web.Controllers
{
	public class HomeController : Controller
	{
		public ActionResult Index()
		{
			return RedirectToAction("Get", 
                        "ViewController.cs");
		}
	}  
}


