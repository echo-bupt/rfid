//-----------------------------------------------------------------------
// <copyright file="TextEventArgs.cs" company="Technology Solutions UK Ltd"> 
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
    /// Provides a text parameter for events
    /// </summary>
    public class TextEventArgs
        : EventArgs
    {
        /// <summary>
        /// Initializes a new instance of the TextEventArgs class
        /// </summary>
        /// <param name="text">The Text value</param>
        public TextEventArgs(string text)
        {
            this.Text = text;
        }

        /// <summary>
        /// Gets the text value
        /// </summary>
        public string Text { get; private set; }
    }
}
