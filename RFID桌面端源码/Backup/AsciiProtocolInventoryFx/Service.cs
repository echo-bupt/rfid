
namespace AsciiProtocolInventory
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;

    using Services;
    using TechnologySolutions.Rfid.AsciiProtocol;
    using ViewModels;

    public class Service
        : IDisposable
    {
        private bool disposed;

        private DisplayResponder displayResponder;

        private ReaderService reader;

        private Properties.Settings settings;

        public Service()
        {
            Instance = this;

            this.displayResponder = new DisplayResponder();
            this.reader = new ReaderService();
            this.settings = Properties.Settings.Default;

            this.ConnectViewModel = new ConnectViewModel(this.reader, this.settings);
            this.MainViewModel = new MainViewModel(this.displayResponder, this.reader.Commander, this.settings);
        }

        public static Service Instance { get; set; }

        public ConnectViewModel ConnectViewModel { get; private set; }

        public MainViewModel MainViewModel { get; private set; }

        public void Dispose()
        {
            this.Dispose(true);
            GC.SuppressFinalize(this);
        }

        protected virtual void Dispose(bool disposing)
        {
            if (!this.disposed)
            {
                if (disposing)
                {
                    Instance = null;
                    this.reader.Disconnect();
                    this.displayResponder.Dispose();

                    // save any changes to settings
                    this.settings.Save();                    
                }

                this.disposed = true;
            }
        }
    }
}
