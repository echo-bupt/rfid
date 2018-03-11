//-----------------------------------------------------------------------
// <copyright file="IReaderConnect.cs" company="Technology Solutions UK Ltd"> 
//     Copyright (c) 2014 Technology Solutions UK Ltd. All rights reserved. 
// </copyright> 
// <author>Robin Stone</author>
//-----------------------------------------------------------------------
namespace AsciiProtocolInventory.Services
{
    using System;

    /// <summary>
    /// Provides methods and properties to manage a reader connection
    /// </summary>
    public interface IReaderConnect
    {
        /// <summary>
        /// Gets a value indicating whether the reader is connected
        /// </summary>
        bool IsConnected { get; }

        /// <summary>
        /// Gets or sets the name of the port used for <see cref="Connect"/>
        /// </summary>
        string PortName { get; set; }

        /// <summary>
        /// Connects to the reader using the current <see cref="PortName"/>
        /// </summary>
        void Connect();

        /// <summary>
        /// Disconnects from the current reader if connected
        /// </summary>
        void Disconnect();        
    }
}
