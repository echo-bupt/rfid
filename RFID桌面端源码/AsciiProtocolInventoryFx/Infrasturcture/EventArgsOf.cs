//-----------------------------------------------------------------------
// <copyright file="EventArgsOf.cs" company="Technology Solutions UK Ltd"> 
//     Copyright (c) 2013 Technology Solutions UK Ltd. All rights reserved. 
// </copyright> 
// <author>Robin Stone</author>
//-----------------------------------------------------------------------
namespace AsciiProtocolInventory
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;

    /// <summary>
    /// Provides data for an event of the specified type
    /// </summary>
    /// <typeparam name="TValue">The data type</typeparam>
    public class EventArgs<TValue>
        : EventArgs
    {
        /// <summary>
        /// Initializes a new instance of the EventArgs class
        /// </summary>
        /// <param name="value">The data value</param>
        public EventArgs(TValue value)
        {
            this.Value = value;
        }

        /// <summary>
        /// Gets the value of the data
        /// </summary>
        public TValue Value { get; private set; }
    }
}
