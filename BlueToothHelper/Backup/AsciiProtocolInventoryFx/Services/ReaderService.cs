//-----------------------------------------------------------------------
// <copyright file="ReaderService.cs" company="Technology Solutions UK Ltd"> 
//     Copyright (c) 2013 Technology Solutions UK Ltd. All rights reserved. 
// </copyright> 
// <author>Robin Stone</author>
//-----------------------------------------------------------------------
namespace AsciiProtocolInventory.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;

    using TechnologySolutions.Rfid.AsciiProtocol;

    /// <summary>
    /// Provides a service to access the interface to the ASCII protocol UHF Reader
    /// </summary>
    public class ReaderService
        : IDisposable, IReaderConnect
    {
        /// <summary>
        /// True once this instance is disposed
        /// </summary>
        private bool disposed;

        /// <summary>
        /// The commander that communicates with the reader
        /// </summary>
        private AsciiCommander commander;

        /// <summary>
        /// Initializes a new instance of the ReaderService class
        /// </summary>
        public ReaderService()
        {
            this.commander = new AsciiCommander();
            this.PortName = "COM32";
        }

        /// <summary>
        /// Gets or sets the name of the port used for <see cref="Connect"/>
        /// </summary>
        public string PortName { get; set; }

        /// <summary>
        /// Gets the <see cref="IAsciiCommandExecuting"/> instance to command or configure
        /// </summary>
        public IAsciiCommandExecuting Commander
        {
            get
            {
                return this.commander;                
            }
        }

        /// <summary>
        /// Gets a value indicating whether the reader is connected
        /// </summary>
        public bool IsConnected
        {
            get
            {
                return this.commander.IsConnected;
            }
        }

        /// <summary>
        /// Connects to the reader using the current <see cref="PortName"/>
        /// </summary>
        public void Connect()
        {
            try
            {
                IAsciiSerialPort serialPort = new SerialPortWrapper(this.PortName);
                this.commander.Connect(serialPort);
            }
            catch (Exception ex)
            {
                System.Diagnostics.Debug.WriteLine(ex.Message);
            }
        }

        /// <summary>
        /// Disconnects from the current reader if connected
        /// </summary>
        public void Disconnect()
        {
            this.commander.Disconnect();
        }

        /// <summary>
        /// Disposes an instance of the ReaderService class
        /// </summary>
        public void Dispose()
        {
            this.Dispose(true);
            GC.SuppressFinalize(this);
        }

        /// <summary>
        /// Disposes an instance of the ReaderService class
        /// </summary>
        /// <param name="disposing">True to dispose managed as well as unmanaged resources</param>
        protected virtual void Dispose(bool disposing)
        {
            if (!this.disposed)
            {
                if (disposing)
                {
                    this.commander.Dispose();
                }

                this.disposed = true;
            }
        }
    }
}
