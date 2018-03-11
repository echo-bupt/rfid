//-----------------------------------------------------------------------
// <copyright file="DisplayResponder.cs" company="Technology Solutions UK Ltd"> 
//     Copyright (c) 2013 Technology Solutions UK Ltd. All rights reserved. 
// </copyright> 
// <author>Robin Stone</author>
//-----------------------------------------------------------------------
namespace TechnologySolutions.Rfid.AsciiProtocol
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;
    using System.Threading;

    /// <summary>
    /// Implements the <see cref="IAsciiCommandResponder"/> to output all received lines to the UI
    /// </summary>
    public class DisplayResponder
        : IAsciiCommandResponder, IDisposable
    {
        /// <summary>
        /// True when an instance of this class is disposed
        /// </summary>
        private bool disposed;

        /// <summary>
        /// Signalled when there are messages in the queue
        /// </summary>
        private AutoResetEvent waitMessage;

        /// <summary>
        /// Thread used to post queued messages to the user interface
        /// </summary>
        private Thread messageThread;

        /// <summary>
        /// The queue of messages to send to the user interface
        /// </summary>
        private Queue<AsciiLineEventArgs> lines;

        /// <summary>
        /// Initializes a new instance of the DisplayResponder class
        /// </summary>
        public DisplayResponder()
        {
            this.lines = new Queue<AsciiLineEventArgs>();
            this.waitMessage = new AutoResetEvent(false);
            this.messageThread = new Thread(this.RunLoop);
            this.messageThread.Name = "DisplayResponderPostMessage";
            this.messageThread.Start();
        }

        /// <summary>
        /// Raised for each line received
        /// </summary>
        public event EventHandler<AsciiLineEventArgs> ReceivedLine;

        /// <summary>
        /// Handles the line by raising the ReceivedLine event. Does not mark as handled
        /// </summary>
        /// <param name="line">The received line</param>
        /// <param name="moreLinesAvailable">True if more lines are already buffered to process</param>
        /// <returns>True if no further responders should process this message</returns>
        public bool ProcessReceivedLine(IAsciiResponseLine line, bool moreLinesAvailable)
        {
            if (!this.disposed)
            {
                this.lines.Enqueue(new AsciiLineEventArgs(line, moreLinesAvailable));
                this.waitMessage.Set();
            }

            return false;
        }

        /// <summary>
        /// Disposes an instance of the DisplayResponder class
        /// </summary>
        public void Dispose()
        {
            this.Dispose(true);
            GC.SuppressFinalize(this);
        }

        /// <summary>
        /// Disposes an instance of the DisplayResponder class
        /// </summary>
        /// <param name="disposing">True to dispose managed as well as unmanaged resources</param>
        protected virtual void Dispose(bool disposing)
        {
            if (!this.disposed)
            {
                if (disposing)
                {
                    this.disposed = true;
                    this.waitMessage.Set();
                }

                this.disposed = true;
            }
        }

        /// <summary>
        /// Raises the <see cref="ReceivedLine"/> event
        /// </summary>
        /// <param name="e">The argumments for the event</param>
        protected virtual void OnReceivedLine(AsciiLineEventArgs e)
        {
            EventHandler<AsciiLineEventArgs> handler;

            handler = this.ReceivedLine;
            if (handler != null)
            {
                handler(this, e);
            }
        }

        /// <summary>
        /// Calls OnReceivedLine with state cast as AsciiLineEventArgs
        /// </summary>
        /// <param name="state">An AsciiLineEventArgs instance to use for the ReceievedLine event</param>
        private void NotifyLine(object state)
        {
            AsciiLineEventArgs e;

            e = state as AsciiLineEventArgs;
            if (e != null)
            {
                this.OnReceivedLine(e);
            }
        }

        /// <summary>
        /// Thread that posts messages to the user interface. Thread is signalled as messages are queued to the dislpay.
        /// Runs until the queue is emptied and then waits again
        /// </summary>
        private void RunLoop()
        {
            while (!this.disposed)
            {
                this.waitMessage.WaitOne();

                while (!this.disposed && this.lines.Count > 0)
                {
                    this.OnReceivedLine(this.lines.Dequeue());
                }
            }
        }
    }
}
