
namespace AsciiProtocolInventory
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;
    using System.Net.Sockets;
    using Services;
    using TechnologySolutions.Rfid.AsciiProtocol;
    using ViewModels;
    using System.IO;

    public class Service
        : IDisposable
    {
        private bool disposed;

        private DisplayResponder displayResponder;

        public ReaderService reader;

        private Properties.Settings settings;

        public serviceSocket socket;

        public int noReadCount = 0;

        public Service()
        {
            Instance = this;
            this.displayResponder = new DisplayResponder();
            this.reader = new ReaderService();
            this.settings = Properties.Settings.Default;
            this.socket = new serviceSocket("127.0.0.1", 2345);
            this.ConnectViewModel = new ConnectViewModel(this.reader, this.settings);
            this.MainViewModel = new MainViewModel(this.displayResponder, this.reader.Commander, this.settings,this.socket);
        }

        public static Service Instance { get; set; }

        public ConnectViewModel ConnectViewModel { get; private set; }

        public MainViewModel MainViewModel { get; private set; }

        public void Dispose()
        {
            this.Dispose(true);
            GC.SuppressFinalize(this);
        }
        public void log(string message)
        {

            FileStream fs2 = new FileStream("log.txt", FileMode.Append, FileAccess.Write);
            StreamWriter sr2 = new StreamWriter(fs2);
            sr2.WriteLine(message);
            sr2.Close();
            fs2.Close();

        }
        public void read(string message)
        {

            FileStream fs2 = new FileStream("read.txt", FileMode.Append, FileAccess.Write);
            StreamWriter sr2 = new StreamWriter(fs2);
            sr2.WriteLine(message);
            sr2.Close();
            fs2.Close();

        }

        public void Ex(string message)
        {

            FileStream fs2 = new FileStream("Exception.txt", FileMode.Append, FileAccess.Write);
            StreamWriter sr2 = new StreamWriter(fs2);
            sr2.WriteLine(message);
            sr2.Close();
            fs2.Close();

        }

        public void data(string message)
        {
            FileStream fs2 = new FileStream("C:\\wamp\\www\\rfid\\app\\data\\data.txt", FileMode.Append, FileAccess.Write);
            StreamWriter sr2 = new StreamWriter(fs2);
            sr2.WriteLine(message);
            sr2.Close();
            fs2.Close();

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
