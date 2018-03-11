//-----------------------------------------------------------------------
// <copyright file="ConnectViewModel.cs" company="Technology Solutions UK Ltd"> 
//     Copyright (c) 2013 Technology Solutions UK Ltd. All rights reserved. 
// </copyright> 
// <author>Robin Stone</author>
//-----------------------------------------------------------------------
namespace AsciiProtocolInventory.ViewModels
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;

    using Services;

    /// <summary>
    /// View model used to connect to a UHF Reader
    /// </summary>
    public class ConnectViewModel
        : PropertyChangedViewModel
    {
        /// <summary>
        /// Backing field for port names
        /// </summary>
        private IEnumerable<string> portNames;

        /// <summary>
        /// Backing field for connection status
        /// </summary>
        private string connectionStatus;

        /// <summary>
        /// The connection to the reader
        /// </summary>
        private IReaderConnect connection;

        /// <summary>
        /// Settings for the connection
        /// </summary>
        private IConnectionSettings settings;

        /// <summary>
        /// Initializes a new instance of the ConnectViewModel class
        /// </summary>
        /// <param name="connection">To manage the connection to the reader</param>
        /// <param name="settings">The connection settings</param>
        public ConnectViewModel(IReaderConnect connection, IConnectionSettings settings)
        {
            if (connection == null)
            {
                throw new ArgumentNullException("connection");
            }

            if (settings == null)
            {
                throw new ArgumentNullException("settings");
            }

            this.connection = connection;
            this.settings = settings;

            this.RefreshPorts();

            this.PortName = this.settings.PortName;
        }

        /// <summary>
        /// Gets a value indicating whether the reader is connected
        /// </summary>
        public bool IsConnected
        {
            get
            {
                return this.connection.IsConnected;
            }
        }

        /// <summary>
        /// Gets a value indicating the connection status of the device
        /// </summary>
        public string ConnectionStatus
        {
            get
            {
                return this.connectionStatus;
            }

            private set
            {
                if (this.connectionStatus != value)
                {
                    this.connectionStatus = value;
                    this.OnPropertyChanged("ConnectionStatus");
                }
            }
        }

        /// <summary>
        /// Gets or sets the selected port name
        /// </summary>
        public string PortName
        {
            get
            {
                return this.connection.PortNameAfter;
            }

            set
            {
                if (this.connection.PortNameAfter != value)
                {
                    this.connection.PortNameAfter = value;
                }

                this.OnPropertyChanged("PortName");
            }
        }

        /// <summary>
        /// Gets or sets the port names available for selection
        /// </summary>
        public IEnumerable<string> PortNames
        {
            get
            {
                return this.portNames;
            }

            set
            {
                if (this.portNames != value)
                {
                    this.portNames = value;
                    this.OnPropertyChanged("PortNames");
                }
            }
        }

        /// <summary>
        /// Returns true if connect can be called
        /// </summary>
        /// <returns>True if the method an be called</returns>
        public bool CanConnect()
        {
            return !this.IsConnected;
        }

        /// <summary>
        /// Returns true if disconnect can be called
        /// </summary>
        /// <returns>True if the method an be called</returns>
        public bool CanDisconnect()
        {
            return this.IsConnected;
        }

        /// <summary>
        /// Returns true if refresh ports can be called
        /// </summary>
        /// <returns>True if the method an be called</returns>
        public bool CanRefreshPorts()
        {
            return true;
        }

        /// <summary>
        /// Connects to the reader
        /// </summary>
        public void Connect()
        {
            this.connection.Connect();
            this.settings.PortName = this.PortName;

            string messageFormat = this.connection.IsConnected ? "Connected on {0}" : "Unable to Connect to {0}";
            this.ConnectionStatus = string.Format(
                System.Globalization.CultureInfo.CurrentUICulture,
                messageFormat,
                this.PortName);
            this.OnPropertyChanged("IsConnected");
        }

        /// <summary>
        /// Disconnects from the reader
        /// </summary>
        public void Disconnect()
        {
            this.connection.Disconnect();
            this.ConnectionStatus = "Disconnected";
            this.OnPropertyChanged("IsConnected");
        }

        /// <summary>
        /// Refreshes the list of available ports that a reader may be connected to
        /// </summary>
        public void RefreshPorts()
        {
            this.PortNames = System.IO.Ports.SerialPort.GetPortNames();
        }
    }
}
