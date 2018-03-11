//-----------------------------------------------------------------------
// <copyright file="ReaderService.cs" company="Technology Solutions UK Ltd"> 
//     Copyright (c) 2013 Technology Solutions UK Ltd. All rights reserved. 
// </copyright> 
// <author>Robin Stone</author>
//-----------------------------------------------------------------------
namespace AsciiProtocolInventory.Services
{
    using System;
    using System.Collections;
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
        private string[] portNames;
        private ArrayList list;

        /// <summary>
        /// Initializes a new instance of the ReaderService class
        /// </summary>
        public ReaderService()
        {
            this.commander = new AsciiCommander();
            this.PortNameAfter = "COM3";
            //全部失败后刷新com口
            this.portNames = System.IO.Ports.SerialPort.GetPortNames();
            this.list = new ArrayList(this.portNames);
            this.IE = list.GetEnumerator();
            this.commander = new AsciiCommander();
        }

        /// <summary>
        /// Gets or sets the name of the port used for <see cref="Connect"/>
        /// </summary>
        public string PortNameAfter { get; set; }

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

        public IEnumerator IE { get; private set; }

        /// <summary>
        /// Connects to the reader using the current <see cref="PortNameAfter"/>
        /// </summary>
        public void Connect()
        {
            try
            {
                IAsciiSerialPort serialPort = new SerialPortWrapper(this.PortNameAfter);
                this.commander.Connect(serialPort);
            }
            catch (Exception ex)
            {
                String message = ex.Message;
                //Service.Instance.log("Connection yichang"+message);
                ConnectAgain();
                System.Diagnostics.Debug.WriteLine(ex.Message);
            }
        }
        public void ConnectAgain()
        {
            try
            {

                //连接到某串口 初始化连接句柄、、
                //连接串口相当于是连接reader设备、、配对后虚拟出来串口 真正连接需要连接串口、、
                if (!this.IE.MoveNext())
                {
                    //Service.Instance.log("ConnectAgain" + "Noport");
                    this.portNames = System.IO.Ports.SerialPort.GetPortNames();
                    this.list = new ArrayList(this.portNames);
                    this.IE = list.GetEnumerator();
                    this.IE.Reset();
                }
                else
                {
                    String port = this.IE.Current.ToString();
                    //Service.Instance.log("ConnectAgain" + "port:" + port);
                    IAsciiSerialPort serialPort = new SerialPortWrapper(port);
                    //连接到串口、、之后 commander 通过方法发送的命令就是 往串口里面写入数据、、 
                    this.commander.Connect(serialPort);
                }
            }
            catch (Exception ex)
            {
                String message = ex.Message;
                //Service.Instance.log("AgainConnection yichang" + message);
            }
        }

        /// <summary>
        /// Disconnects from the current reader if connected
        /// </summary>
        public void Disconnect()
        {
            this.commander.Disconnect();
            this.commander.Dispose();
            Dispose();
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
