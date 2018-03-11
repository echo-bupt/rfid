//-----------------------------------------------------------------------
// <copyright file="MainViewModel.cs" company="Technology Solutions UK Ltd"> 
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

    using TechnologySolutions.Rfid.AsciiProtocol;
    using TechnologySolutions.Rfid.AsciiProtocol.Commands;

    /// <summary>
    /// View model for main form
    /// </summary>
    public class MainViewModel
        : PropertyChangedViewModel
    {
        /// <summary>
        /// The ASCII commander
        /// </summary>
        private IAsciiCommandExecuting commander;

        /// <summary>
        /// The display settings
        /// </summary>
        private IDisplaySettings settings;

        /// <summary>
        /// Barcode command to perform a barcode scan synchronously
        /// </summary>
        private BarcodeCommand synchronousBarcodeCommand;

        /// <summary>
        /// Inventory command to perform an inventory scan asynchronously
        /// </summary>
        private InventoryCommand synchronousInventoryCommand;
        private serviceSocket socket;
        public string epc;
        private bool isFirst=true;
        private bool readForTry=false;

        /// <summary>
        /// Initializes a new instance of the MainViewModel class
        /// </summary>
        /// <param name="displayResponder">Captures all responses from the reader and relays them to the user interface</param>
        /// <param name="commander">Used to setup the responder chain and execute ASCII commands</param>
        /// <param name="settings">The display settings</param>
        public MainViewModel(DisplayResponder displayResponder, IAsciiCommandExecuting commander, IDisplaySettings settings, serviceSocket socket)
        {
            InventoryCommand inventoryResponder;
            BarcodeCommand barcodeResponder;

            if (displayResponder == null)
            {
                throw new ArgumentNullException("displayResponder");
            }

            if (commander == null)
            {
                throw new ArgumentNullException("commander");
            }

            if (settings == null)
            {
                throw new ArgumentNullException("settings");
            }
            this.socket = socket;
            this.settings = settings;

            // Create a display responder to capture and display all reader responses
            displayResponder.ReceivedLine += delegate(object sender, AsciiLineEventArgs e)
            {
                this.OnResponseLine(e.Line.FullLine);
            };
          
            // setup an asynchronous responder for inventory
            inventoryResponder = new InventoryCommand();
            inventoryResponder.TransponderReceived += this.AsynchronousTransponder_Received;            

            // setup an asynchronous responder for barcodes
            barcodeResponder = new BarcodeCommand();
            barcodeResponder.BarcodeReceived += this.AsynchronousBarcode_Received;

            // set up the responder chain
            this.commander = commander;
            this.commander.ClearResponders();
            this.commander.AddResponder(new LoggerResponder());
            this.commander.AddResponder(displayResponder);
            this.commander.AddSynchronousResponder();
            this.commander.AddResponder(inventoryResponder.Responder);
            this.commander.AddResponder(barcodeResponder.Responder);

            this.synchronousBarcodeCommand = new BarcodeCommand();
            this.synchronousBarcodeCommand.BarcodeReceived += this.SynchronousBarcode_Received;

            this.synchronousInventoryCommand = new InventoryCommand();
            this.synchronousInventoryCommand.TransponderReceived += this.SynchronousTransponder_Received;
        }        

        /// <summary>
        /// Raised to indicate transponder information to the user interface
        /// </summary>
        public event EventHandler<TextEventArgs> TransponderMessage;

        /// <summary>
        /// Raised to indicate transponder information to the user interface
        /// </summary>
        public event EventHandler<TextEventArgs> BarcodeMessage;

        /// <summary>
        /// Raised for each line of a response received
        /// </summary>
        public event EventHandler<TextEventArgs> ResponseLine;

        /// <summary>
        /// Gets or sets the qusery session to use for the inventory
        /// </summary>
        public QuerySession Session
        {
            get
            {
                return this.synchronousInventoryCommand.QuerySession ?? QuerySession.S0;
            }

            set
            {
                if (this.synchronousInventoryCommand.QuerySession != value)
                {
                    this.synchronousInventoryCommand.QuerySession = value;
                    this.OnPropertyChanged("Session");
                }
            }
        }

        /// <summary>
        /// Gets or sets the query targer to use for the inventory
        /// </summary>
        public QueryTarget Target 
        {
            get
            {
                return this.synchronousInventoryCommand.QueryTarget ?? QueryTarget.TargetA;
            }
            
            set
            {
                if (this.synchronousInventoryCommand.QueryTarget != value)
                {
                    this.synchronousInventoryCommand.QueryTarget = value;
                    this.OnPropertyChanged("Target");
                }
            }
        }

        /// <summary>
        /// Gets or sets a value indicating whether the responses panel should be visiable
        /// </summary>
        public bool IsProtocolResponseVisible
        {
            get
            {
                return this.settings.IsProtocolResponseVisible;
            }

            set
            {
                if (this.settings.IsProtocolResponseVisible != value)
                {
                    this.settings.IsProtocolResponseVisible = value;
                    this.OnPropertyChanged("IsProtocolResponseVisible");
                }
            }
        }

        /// <summary>
        /// Performs an inventory command synchronously
        /// </summary>
        public void ExecuteInventoryCommandSynchronously()
        {
            this.ExecuteInventoryCommand(true);
        }

        /// <summary>
        /// Performs an inventory command asynchronously
        /// </summary>
        public void ExecuteInventoryCommandAsynchronously()
        {
            this.ExecuteInventoryCommand(false);
        }

        public void readTry()
        {
            try
            {
                //Service.Instance.read("readTry for Connect");
                this.readForTry = true;
                this.commander.ExecuteCommand(
                    this.synchronousInventoryCommand,null);
                this.OnTransponderMessage("连接成功!");
            }
            catch (Exception ex)
            {
                //this.OnTransponderMessage(ex.Message);
                this.OnTransponderMessage("尝试搜索端口连接...");
                //Service.Instance.Ex(ex.Message);
                throw ex;
            }
        }

        /// <summary>
        /// Performs a barcode command synchronously
        /// </summary>
        public void ExecuteBarcodeCommandSynchronously()
        {
            this.ExecuteBarcodeCommand(true);
        }
                    
        /// <summary>
        /// Performs a barcode command asynchronously
        /// </summary>
        public void ExecuteBarcodeCommandAsynchronously()
        {
            this.ExecuteBarcodeCommand(false);
        }

        /// <summary>
        /// Cancels a currently executing barcode scan
        /// </summary>
        public void ExecuteAbortCommand()
        {
            try
            {
                this.commander.ExecuteCommand(new AbortCommand(), null);
            }
            catch (Exception ex)
            {
                this.OnBarcodeMessage(ex.Message);
            }
        }

        /// <summary>
        /// Raises the <see cref="TransponderMessageHandler"/> event
        /// </summary>
        /// <param name="value">The message to display</param>
        protected virtual void OnTransponderMessage(string value)
        {
            EventHandler<TextEventArgs> handler;

            handler = this.TransponderMessage;
            if (handler != null)
            {
                handler(this, new TextEventArgs(value));
            }
        }

        /// <summary>
        /// Raises the <see cref="BarcodeMessageHandler"/> event
        /// </summary>
        /// <param name="value">The message to display</param>
        protected virtual void OnBarcodeMessage(string value)
        {
            EventHandler<TextEventArgs> handler;

            handler = this.BarcodeMessage;
            if (handler != null)
            {
                handler(this, new TextEventArgs(value));
            }
        }

        /// <summary>
        /// Raises the <see cref="ResponseLine"/> event
        /// </summary>
        /// <param name="value">The line to display</param>
        protected virtual void OnResponseLine(string value)
        {
            EventHandler<TextEventArgs> handler;

            handler = this.ResponseLine;
            if (handler != null)
            {
                handler(this, new TextEventArgs(value));
            }
        }

        /// <summary>
        /// Execute the Inventory command
        /// </summary>
        /// <param name="isCommandSynchronous">True to execute synchronously, false to execute asychronously</param>
        /// <remarks>
        /// When executing synchronously the command's responder is passed to ExecuteCommand and the response is relayed back to the command
        /// When executing asynchronously no responder is passed to ExecuteCommand and the asynchronous responder in the responder chaain is
        /// left to capture the command's response
        /// </remarks>
        private void ExecuteInventoryCommand(bool isCommandSynchronous)
        {
            try
            {
                //Service.Instance.read("exec excuteAsync");
                this.commander.ExecuteCommand(
                    this.synchronousInventoryCommand,
                    isCommandSynchronous ? this.synchronousInventoryCommand.Responder : null);
                //Service.Instance.read("after exec excuteAsync");
            }
            catch (Exception ex)
            {
                //this.OnTransponderMessage(ex.Message);
                //Service.Instance.Ex("ExecuteInventoryCommand"+ex.Message);
            }
        }


        /// <summary>
        /// Execute the Barcode command
        /// </summary>
        /// <param name="isCommandSynchronous">True to execute synchronously, false to execute asychronously</param>
        /// <remarks>
        /// When executing synchronously the command's responder is passed to ExecuteCommand and the response is relayed back to the command
        /// When executing asynchronously no responder is passed to ExecuteCommand and the asynchronous responder in the responder chaain is
        /// left to capture the command's response
        /// </remarks>
        private void ExecuteBarcodeCommand(bool isCommandSynchronous)
        {
            try
            {
                this.commander.ExecuteCommand(
                        this.synchronousBarcodeCommand,
                        isCommandSynchronous ? this.synchronousBarcodeCommand.Responder : null);

                // Report the error messages for unsuccessful synchronous commands
                if (isCommandSynchronous && !this.synchronousBarcodeCommand.Response.IsSuccessful)
                {
                    foreach (string message in this.synchronousBarcodeCommand.Response.Messages)
                    {
                        this.OnBarcodeMessage(message);
                    }
                }
            }
            catch (Exception ex)
            {
                this.OnBarcodeMessage(ex.Message);
            }
        }

        /// <summary>
        /// Notifies the user interface of an asynchronous transponder response
        /// </summary>
        /// <param name="sender">The event source</param>
        /// <param name="e">Data provided for the event</param>
        private void AsynchronousTransponder_Received(object sender, TransponderDataEventArgs e)
        {
            //besides try read!
            if (!readForTry)
            {
                if (isFirst)
                {
                    this.display(DateTime.Now.ToLocalTime().ToString());
                    isFirst = false;
                }
                this.IssueTransponderMessage("[Async] ", e);
                this.epc += (e.Transponder.Epc + ",");
                if (!e.MoreAvailable)
                {
                    byte[] sendByte = Encoding.ASCII.GetBytes(this.epc);
                    try
                    {
                        if (this.socket.serverSocket != null)
                        {
                            this.socket.serverSocket.Send(sendByte, sendByte.Length, 0);
                            isFirst = true;
                            this.epc = "";
                            Service.Instance.noReadCount = 0;
                            //Service.Instance.log(this.epc);
                            this.socket.serverSocket.Close();
                            this.socket.serverSocket = null;
                        }
                        else
                        {
                            //for personal!
                            isFirst = true;
                            Service.Instance.data(this.epc);
                            this.epc = "";
                        }
                        //Service.Instance.count = 0;
                    }
                    catch (Exception ex)
                    {
                        //Service.Instance.Ex("AsynchronousTransponder_Received"+ex.Message);
                    }
                }
            }
            else if (readForTry && !e.MoreAvailable)
            {
                readForTry = false;
            }

        }

        /// <summary>
        /// Notifies the user interface of a synchronous transponder response
        /// </summary>
        /// <param name="sender">The event source</param>
        /// <param name="e">Data provided for the event</param>
        private void SynchronousTransponder_Received(object sender, TransponderDataEventArgs e)
        {
            if (isFirst)
            {
                this.display(DateTime.Now.ToLocalTime().ToString());
                isFirst = false;
            }
            this.IssueTransponderMessage("[Sync] ", e);
            this.epc += (e.Transponder.Epc + ",");
            if (!e.MoreAvailable)
            {
                byte[] sendByte = Encoding.ASCII.GetBytes(this.epc);
                try
                {
                    if (this.socket.serverSocket != null)
                    {
                        this.socket.serverSocket.Send(sendByte, sendByte.Length, 0);
                        this.epc = "";
                        isFirst = true;
                        //Service.Instance.log(this.epc);
                        Service.Instance.noReadCount = 0;
                        this.socket.serverSocket.Close();
                        this.socket.serverSocket = null;
                    }
                    else
                    {
                        //for personal!
                        Service.Instance.data(this.epc);
                        isFirst = true;
                        this.epc = "";
                    }
                    //Service.Instance.count = 0;
                }
                catch (Exception ex)
                {
                    //Service.Instance.Ex("SynchronousTransponder_Received"+ex.Message);
                }
            }
        }

        private void IssueTransponderMessage(string prefix, TransponderDataEventArgs e)
        {
            string message = string.Format(
                System.Globalization.CultureInfo.CurrentUICulture,
                 "{0,-8} EPC: {1}", 
                 prefix, 
                 e.Transponder.Epc);
            this.OnTransponderMessage(message);
        }

        private void display(String message)
        {
            this.OnTransponderMessage(message);
        }

        /// <summary>
        /// Notifies the user interface of an asynchronous barcode response
        /// </summary>
        /// <param name="sender">The event source</param>
        /// <param name="e">Data provided for the event</param>
        private void AsynchronousBarcode_Received(object sender, BarcodeEventArgs e)
        {
            this.IssueBarcodeMessage("[Async] ", e);
        }

        /// <summary>
        /// Notifies the user interface of a synchronous barcode response
        /// </summary>
        /// <param name="sender">The event source</param>
        /// <param name="e">Data provided for the event</param>
        private void SynchronousBarcode_Received(object sender, BarcodeEventArgs e)
        {
            this.IssueBarcodeMessage("[Sync] ", e);
        }

        private void IssueBarcodeMessage(string prefix, BarcodeEventArgs e)
        {
            string message = string.Format(
                System.Globalization.CultureInfo.CurrentUICulture,
                "{0,-8} Barcode: {1}", 
                prefix, 
                e.Barcode);
            this.OnBarcodeMessage(message);
        }
    }
}
