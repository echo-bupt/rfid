//-----------------------------------------------------------------------
// <copyright file="Program.cs" company="Technology Solutions UK Ltd"> 
//     Copyright (c) 2013 Technology Solutions UK Ltd. All rights reserved. 
// </copyright> 
// <author>Robin Stone</author>
//-----------------------------------------------------------------------
namespace AsciiProtocolInventory
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Windows.Forms;

    using log4net;

    /// <summary>
    /// Provides the entry point for the application
    /// </summary>
    public static class Program
    {
        /// <summary>
        /// The main entry point for the application.
        /// </summary>
        [STAThread]
        public static void Main()
        {
            ILog log;

            log4net.Config.XmlConfigurator.Configure();
            log = LogManager.GetLogger(typeof(Program));
            log.Info("--- Application Start ---");

            using (Service service = new Service())
            {
                Application.EnableVisualStyles();
                Application.SetCompatibleTextRenderingDefault(false);
                Application.Run(new Views.MainForm());
            }

            log.Info("--- Application End ---");

            LogManager.Shutdown();
        }
    }
}
